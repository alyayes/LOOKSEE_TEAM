@extends('layouts.mainAdmin')

@section('title', 'Orders List')

@section('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
/* CSS Dasar */
* { box-sizing: border-box; }

/* INPUT SEARCH: Dibatasi lebarnya dan diposisikan di tengah */
.search-container {
    max-width: 500px; /* Lebar maksimum input pencarian */
    margin-bottom: 20px;
}
#myInput { 
    width: 100%; 
    padding: 12px 20px 12px 40px; 
    font-size: 16px; 
    border: 1px solid #ddd; 
    border-radius: 5px;
    background-image: url('/css/searchicon.png'); 
    background-position: 10px 10px; 
    background-repeat: no-repeat; 
}

/* WRAPPER TABEL: Dibatasi lebarnya agar tidak scroll */
.table-responsive-wrapper {
    max-width: 100%; /* Pastikan tidak melebihi lebar wadah utama */
    overflow-x: auto; /* Scroll horizontal hanya muncul jika benar-benar diperlukan */
    margin-bottom: 50px;
    border: 1px solid #ddd; /* Border dipindah ke wrapper */
    background-color: #fff;
}

/* TABEL */
#myTable { 
    border-collapse: collapse; 
    width: 100%; 
    font-size: 16px; 
    border: none; /* Border dihilangkan dari tabel */
}

#myTable th, #myTable td { 
    text-align: left; 
    padding: 12px; 
    border: 1px solid #ddd; 
    vertical-align: middle; 
}

#myTable tr.header { 
    background-color: rgb(255, 234, 247); 
    font-weight: bold;
}

#myTable tr:hover { 
    background-color: #f1f1f1; 
}

/* KHUSUS KOLOM PRODUCT: Agar teks tidak terlalu panjang */
#myTable td:nth-child(3) {
    max-width: 250px; /* Batas lebar kolom produk */
    white-space: normal; /* Teks dibungkus ke baris baru */
    word-wrap: break-word;
}

/* Styling Select Status */
.status-select {
    font-weight: 600;
    padding: 5px 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    cursor: pointer;
    width: 100%;
}
.status-pending { background-color: #ffc107; color: #000; }
.status-prepared { background-color: #17a2b8; color: white; }
.status-shipped { background-color: #007bff; color: white; }
.status-completed { background-color: #28a745; color: white; }
</style>
@endsection

@section('content')

<div style="padding: 1.5rem;">
    
    <div class="search-container">
        <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Cari order...">
    </div>

    <div class="table-responsive-wrapper">
        <table id="myTable">
            <tr class="header">
                <th style="width:8%;">ORDER ID</th>
                <th style="width:10%;">CUSTOMER</th>
                <th>PRODUCT</th> <th style="width:12%;">ORDER DATE</th>
                <th style="width:12%;">AMOUNT</th>
                <th style="width:10%;">PAYMENT</th>
                <th style="width:10%;">BANK/E-WALLET</th>
                <th style="width:12%;">STATUS</th>
                <th style="width:6%;">ACTION</th>
            </tr>

            @forelse ($orders as $row)
            <tr>
                <td>#{{ $row['order_id'] }}</td>
                <td>{{ $row['username'] }}</td>
                <td>{!! $row['nama_produk_list'] !!}</td>
                <td>{{ $row['order_date'] }}</td>
                <td>Rp {{ number_format($row['total_price'], 0, ',', '.') }}</td>
                <td>{{ $row['metode_pembayaran'] }}</td>
                <td>
                    @if($row['metode_pembayaran'] == 'Bank Transfer')
                        {{ $row['bank_name'] }}
                    @elseif($row['metode_pembayaran'] == 'E-Wallet')
                        {{ $row['ewallet_provider_name'] }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    <select 
                        class="status-select status-{{ strtolower($row['status']) }}" 
                        data-order-id="{{ $row['order_id'] }}"
                        data-original-status="{{ strtolower($row['status']) }}"
                        onchange="updateStatus(this)"
                    >
                        <option value="pending" {{ strtolower($row['status']) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="prepared" {{ strtolower($row['status']) == 'prepared' ? 'selected' : '' }}>Prepared</option>
                        <option value="shipped" {{ strtolower($row['status']) == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="completed" {{ strtolower($row['status']) == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </td>
                <td style="text-align: center;">
                    <a href="{{ route('admin.order.detail', ['order_id' => $row['order_id']]) }}" title="Lihat Detail">
                        <i class='bx bx-show-alt' style="font-size: 20px;"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" style="text-align: center;">Tidak ada data order.</td></tr>
            @endforelse
        </table>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Fungsi Search
function myFunction() {
    var input = document.getElementById("myInput").value.toUpperCase();
    var table = document.getElementById("myTable");
    var tr = table.getElementsByTagName("tr");
    for (var i = 1; i < tr.length; i++) {
        var td = tr[i].getElementsByTagName("td");
        var found = false;
        const cols = [0,1,2,7]; 
        for (var j = 0; j < td.length; j++) {
            if (cols.includes(j) && td[j]) {
                if (td[j].textContent.toUpperCase().indexOf(input) > -1 || td[j].querySelector('select')?.value.toUpperCase().indexOf(input) > -1) { 
                    found = true; break; 
                }
            }
        }
        tr[i].style.display = found ? "" : "none";
    }
}

// Fungsi Update Status
function updateStatus(element) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const updateStatusUrl = '{{ route('admin.order.updateStatus') }}';
    
    const orderId = element.getAttribute('data-order-id');
    const newStatus = element.value;
    const oldStatus = element.getAttribute('data-original-status');

    element.className = 'status-select status-' + newStatus;

    fetch(updateStatusUrl, {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json', 
            'X-CSRF-TOKEN': csrfToken 
        },
        body: JSON.stringify({ order_id: orderId, status: newStatus })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            alert('Status berhasil diupdate menjadi: ' + newStatus);
            element.setAttribute('data-original-status', newStatus);
        } else {
            alert('Gagal: '+ data.message);
            element.value = oldStatus;
            element.className = 'status-select status-' + oldStatus;
        }
    })
    .catch(err => { 
        console.error(err); 
        alert('Terjadi kesalahan jaringan.');
        element.value = oldStatus;
        element.className = 'status-select status-' + oldStatus;
    });
}
</script>
@endsection