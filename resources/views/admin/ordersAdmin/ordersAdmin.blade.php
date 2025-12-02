@extends('layouts.mainAdmin')

@section('title', 'Orders List')

@section('styles')
<style>
* { box-sizing: border-box; }

#myInput {
    width: 45%;
    padding:12px 20px 12px 40px;
    font-size:16px;
    border:1px solid #ddd;
    margin:20px 0 5px;
    background-image:url('/css/searchicon.png');
    background-position:10px 10px;
    background-repeat:no-repeat;
}

#myTable {
    border-collapse: collapse;
    width:100%;
    border:1px solid #ddd;
    font-size:18px;
    margin-bottom:50px;
}

#myTable th, #myTable td {
    text-align:left;
    padding:12px;
    border:1px solid #ddd;
}

#myTable tr.header,
#myTable tr:hover {
    background-color: rgb(255, 234, 247);
}

/* BADGE STATUS */
.status-badge {
    padding: .35em .65em;
    font-size:.75em;
    font-weight:700;
    border-radius:.375rem;
    cursor:pointer;
    color:#fff;
    display: inline-block;
}

.status-badge.pending { background-color: rgb(255,193,7); }
.status-badge.prepared { background-color: rgb(253,152,0); }
.status-badge.shipped { background-color: rgb(148,190,253); }
.status-badge.completed { background-color: rgb(60,199,134); }

/* CONTAINER */
.status-container {
    position: relative;
    width: fit-content;
}

/* DROPDOWN */
.custom-dropdown-menu {
    position: absolute;
    top: 110%;
    left: 0;
    z-index: 99999;
    min-width: 10rem;
    padding: .5rem 0;
    font-size: 0.9rem;
    background-color: #fff;
    border: 1px solid rgba(0,0,0,.15);
    border-radius: .25rem;
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.175);
    display: none;
}

.custom-dropdown-menu.show {
    display: block;
}

.custom-dropdown-item {
    display: block;
    padding: .4rem 1rem;
    cursor: pointer;
    text-decoration: none;
    color: #000;
}

/* ✅ hanya yang aktif yang pink */
.custom-dropdown-item.active-option {
    background-color: rgb(255,161,200);
    color: #fff;
}

.custom-dropdown-item:hover:not(.active-option) {
    background-color: #f8f9fa;
}
</style>
@endsection

@section('content')

<div class="propic">
    <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Cari order...">

    <table id="myTable">
        <tr class="header">
            <th>ORDER ID</th>
            <th>CUSTOMER</th>
            <th>PRODUCT</th>
            <th>ORDER DATE</th>
            <th>AMOUNT</th>
            <th>PAYMENT</th>
            <th>BANK/E-WALLET</th>
            <th>STATUS</th>
            <th>ACTION</th>
        </tr>

        @forelse ($orders as $row)

            @php
                $status_lower = Str::lower($row['status']);

                $bank_ewallet_info = '-';
                if ($row['metode_pembayaran'] === 'Bank Transfer') {
                    $bank_ewallet_info = $row['bank_name'] ?? '-';
                } elseif ($row['metode_pembayaran'] === 'E-Wallet') {
                    $bank_ewallet_info = $row['ewallet_provider_name'] ?? '-';
                }
            @endphp

            <tr>
                <td>#{{ $row['order_id'] }}</td>
                <td>{{ $row['username'] }}</td>
                <td>{!! $row['nama_produk_list'] !!}</td>
                <td>{{ $row['order_date'] }}</td>
                <td>Rp {{ number_format($row['total_price'], 2, ',', '.') }}</td>
                <td>{{ $row['metode_pembayaran'] }}</td>
                <td>{{ $bank_ewallet_info }}</td>

                <td>
                    <div class="status-container">
                        <span class="status-badge {{ $status_lower }}" data-order-id="{{ $row['order_id'] }}">
                            {{ ucfirst($status_lower) }} <i class="fa fa-caret-down"></i>
                        </span>

                        <div class="custom-dropdown-menu" id="dropdown-{{ $row['order_id'] }}">
                            @foreach(['pending','prepared','shipped','completed'] as $status)
                                <a class="custom-dropdown-item {{ $status_lower == $status ? 'active-option' : '' }}"
                                   data-status="{{ $status }}">
                                    {{ ucfirst($status) }}
                                </a>
                            @endforeach
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
    let input = document.getElementById("myInput").value.toUpperCase();
    let tr = document.getElementById("myTable").getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) {
        let td = tr[i].getElementsByTagName("td");
        let found = false;

        for (let j = 0; j < td.length; j++) {
            if (td[j].innerText.toUpperCase().includes(input)) {
                found = true;
                break;
            }
        }
        tr[i].style.display = found ? "" : "none";
    }
}

document.addEventListener('DOMContentLoaded', function() {

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const updateStatusUrl = "{{ route('admin.order.updateStatus') }}";

    document.querySelectorAll('.status-badge').forEach(badge => {
        badge.addEventListener('click', function (e) {
            e.stopPropagation();

            let orderId = this.dataset.orderId;
            let dropdown = document.getElementById('dropdown-' + orderId);

            document.querySelectorAll('.custom-dropdown-menu').forEach(menu => {
                if (menu !== dropdown) menu.classList.remove('show');
            });

            dropdown.classList.toggle('show');
        });
    });

    document.querySelectorAll('.custom-dropdown-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            let newStatus = this.dataset.status;
            let dropdown = this.closest('.custom-dropdown-menu');
            let orderId = dropdown.id.replace('dropdown-', '');
            let badge = document.querySelector(`.status-badge[data-order-id="${orderId}"]`);

            fetch(updateStatusUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    order_id: orderId,
                    status: newStatus
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {

                    // reset class badge
                    badge.classList.remove('pending','prepared','shipped','completed');
                    badge.classList.add(newStatus);

                    badge.innerHTML =
                        newStatus.charAt(0).toUpperCase() +
                        newStatus.slice(1) +
                        ' <i class="fa fa-caret-down"></i>';

                    // set active option
                    dropdown.querySelectorAll('.custom-dropdown-item')
                           .forEach(opt => opt.classList.remove('active-option'));

                    this.classList.add('active-option');
                    dropdown.classList.remove('show');

                } else {
                    alert(data.message);
                }
            })
            .catch(() => alert('Terjadi kesalahan server!'));
        });
    });

    document.addEventListener('click', function () {
        document.querySelectorAll('.custom-dropdown-menu').forEach(menu => menu.classList.remove('show'));
    });

});
</script>

@endsection
