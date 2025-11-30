@extends('layouts.mainAdmin')

@section('title', 'Orders List')

@section('styles')
<style>
/* Styling sama persis seperti sebelumnya */
* { box-sizing: border-box; }
#myInput { width: 45%; padding:12px 20px 12px 40px; font-size:16px; border:1px solid #ddd; margin:20px 0 5px; background-image:url('/css/searchicon.png'); background-position:10px 10px; background-repeat:no-repeat; }
#myTable { border-collapse: collapse; width:100%; border:1px solid #ddd; font-size:18px; margin-bottom:50px; }
#myTable th, #myTable td { text-align:left; padding:12px; border:1px solid #ddd; }
#myTable tr.header, #myTable tr:hover { background-color: rgb(255, 234, 247); }
.status-badge { padding: .35em .65em; font-size:.75em; font-weight:700; border-radius:.375rem; cursor:pointer; color:#fff; }
.status-badge.pending { background-color: rgba(255,193,7,.8); }
.status-badge.prepared { background-color: rgb(253,152,0); }
.status-badge.shipped { background-color: rgb(148,190,253); }
.status-badge.completed { background-color: rgb(60,199,134); }
.custom-dropdown-menu { display:none; position:absolute; top:100%; left:0; z-index:1000; min-width:10rem; padding:.5rem 0; font-size:1rem; color:#212529; background-color:#fff; border:1px solid rgba(0,0,0,.15); border-radius:.25rem; box-shadow:0 .5rem 1rem rgba(0,0,0,.175); }
.custom-dropdown-menu.show { display:block; }
.custom-dropdown-item { display:block; padding:.25rem 1rem; cursor:pointer; text-decoration:none; }
.custom-dropdown-item.active-option { background-color: rgb(255,161,200); color:#fff; }
.custom-dropdown-item:hover { background-color:#e9ecef; }
</style>
@endsection

@section('content')
<div class="propic">
    <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Cari order...">

    <table id="myTable">
        <tr class="header">
            <th style="width:10%;">ORDER ID</th>
            <th style="width:5%;">CUSTOMER</th>
            <th style="width:17%;">PRODUCT</th>
            <th style="width:15%;">ORDER DATE</th>
            <th style="width:12%;">AMOUNT</th>
            <th style="width:10%;">PAYMENT METHOD</th>
            <th style="width:8%;">BANK/E-WALLET</th>
            <th style="width:8%;">STATUS</th>
            <th style="width:5%;">ACTION</th>
        </tr>

        @forelse ($orders as $row)
        @php
            $status_lower = strtolower($row['status']);
            $bank_ewallet_info = '-';
            if ($row['metode_pembayaran'] == 'Bank Transfer') $bank_ewallet_info = $row['bank_name'] ?? '-';
            elseif ($row['metode_pembayaran'] == 'E-Wallet') $bank_ewallet_info = $row['ewallet_provider_name'] ?? '-';
        @endphp
        <tr>
            <td>#{{ $row['order_id'] }}</td>
            <td>{{ htmlspecialchars($row['username']) }}</td>
            <td>{!! $row['nama_produk_list'] !!}</td>
            <td>{{ htmlspecialchars($row['order_date']) }}</td>
            <td>Rp {{ number_format($row['total_price'], 2, ',', '.') }}</td>
            <td>{{ htmlspecialchars($row['metode_pembayaran']) }}</td>
            <td>{{ htmlspecialchars($bank_ewallet_info) }}</td>
            <td>
                <div class="status-container">
                    <span class="status-badge {{ $status_lower }}" data-order-id="{{ $row['order_id'] }}">
                        {{ ucfirst(htmlspecialchars($row['status'])) }} <i class="fa fa-caret-down"></i>
                    </span>
                    <div class="custom-dropdown-menu" id="dropdown-{{ $row['order_id'] }}">
                        <a class="custom-dropdown-item {{ $status_lower == 'pending' ? 'active-option' : '' }}" href="#" data-status="pending">Pending</a>
                        <a class="custom-dropdown-item {{ $status_lower == 'prepared' ? 'active-option' : '' }}" href="#" data-status="prepared">Prepared</a>
                        <a class="custom-dropdown-item {{ $status_lower == 'shipped' ? 'active-option' : '' }}" href="#" data-status="shipped">Shipped</a>
                        <a class="custom-dropdown-item {{ $status_lower == 'completed' ? 'active-option' : '' }}" href="#" data-status="completed">Completed</a>
                    </div>
                </div>
            </td>
            <td>
                <a href="{{ route('admin.order.detail', ['order_id' => $row['order_id']]) }}">
                    <i class='bx bx-show-alt'></i>
                </a>

            </td>
        </tr>
        @empty
        <tr><td colspan="9">Tidak ada data</td></tr>
        @endforelse
    </table>
</div>

<script>
function myFunction() {
    var input = document.getElementById("myInput").value.toUpperCase();
    var table = document.getElementById("myTable");
    var tr = table.getElementsByTagName("tr");
    for (var i = 1; i < tr.length; i++) {
        var td = tr[i].getElementsByTagName("td");
        var found = false;
        const cols = [0,1,2,5,6,7];
        for (var j = 0; j < td.length; j++) {
            if (cols.includes(j) && td[j]) {
                if ((td[j].textContent || td[j].innerText).toUpperCase().indexOf(input) > -1) { found = true; break; }
            }
        }
        tr[i].style.display = found ? "" : "none";
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const updateStatusUrl = '{{ route('admin.order.updateStatus') }}';

    document.querySelectorAll('.status-badge').forEach(badge => {
        badge.addEventListener('click', function(event) {
            event.stopPropagation();
            const orderId = this.dataset.orderId;
            const dropdown = document.getElementById('dropdown-' + orderId);
            document.querySelectorAll('.custom-dropdown-menu').forEach(menu => menu !== dropdown && menu.classList.remove('show'));
            dropdown.classList.toggle('show');
        });
    });

    document.querySelectorAll('.custom-dropdown-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault(); e.stopPropagation();
            const newStatus = this.dataset.status;
            const dropdown = this.closest('.custom-dropdown-menu');
            const orderId = dropdown.id.replace('dropdown-', '');
            const badge = document.querySelector(`.status-badge[data-order-id="${orderId}"]`);

            fetch(updateStatusUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ order_id: orderId, status: newStatus })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    badge.classList.remove('pending','prepared','shipped','completed');
                    badge.classList.add(newStatus);
                    badge.innerHTML = newStatus.charAt(0).toUpperCase()+newStatus.slice(1)+' <i class="fa fa-caret-down"></i>';
                    dropdown.querySelectorAll('.custom-dropdown-item').forEach(opt => opt.classList.remove('active-option'));
                    this.classList.add('active-option');
                    dropdown.classList.remove('show');
                    alert(data.message);
                } else alert('Gagal: '+data.message);
            })
            .catch(err => { console.error(err); alert('Terjadi kesalahan jaringan atau server.'); });
        });
    });

    document.addEventListener('click', function(){ document.querySelectorAll('.custom-dropdown-menu').forEach(menu => menu.classList.remove('show')); });
});
</script>
@endsection
