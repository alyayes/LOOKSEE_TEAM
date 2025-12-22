@extends('layouts.mainAdmin')

@section('title', 'Dashboard Admin')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/dashboardAdmin.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
* {
    box-sizing: border-box;
}

#myTable {
    border-collapse: collapse;
    width: 100%;
    border: 1px solid #ddd;
    font-size: 15px;
}

#myTable th, #myTable td {
    padding: 10px;
    border: 1px solid #ddd;
}

#myTable tr.header,
#myTable tr:hover {
    background-color: rgb(255, 234, 247);
}

.status-select {
    font-weight: 600;
}

.status-pending { background-color: #ffc107; }
.status-prepared { background-color: #17a2b8; color: white; }
.status-shipped { background-color: #007bff; color: white; }
.status-completed { background-color: #28a745; color: white; }
</style>
@endsection

@section('content')
<div class="page-wrapper">
    <div class="page-content">

        <!-- ===== DASHBOARD CARDS ===== -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 mb-4">

            <div class="col">
                <div class="card radius-10 border-start border-info border-4">
                    <div class="card-body">
                        <h3 class="text-info">{{ number_format($user_count) }}</h3>
                        <p>Total Users</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card radius-10 border-start border-danger border-4">
                    <div class="card-body">
                        <h3 class="text-danger">{{ number_format($product_count) }}</h3>
                        <p>Total Products</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card radius-10 border-start border-success border-4">
                    <div class="card-body">
                        <h3 class="text-success">{{ number_format($order_count) }}</h3>
                        <p>Total Orders</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card radius-10 border-start border-warning border-4">
                    <div class="card-body">
                        <h3 class="text-warning">
                            Rp {{ number_format($total_sales, 2, ',', '.') }}
                        </h3>
                        <p>Total Sales</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- ===== RECENT ORDERS ===== -->
        <h5 class="mb-3">Recent Orders</h5>

        <table id="myTable">
            <tr class="header">
                <th>ORDER ID</th>
                <th>CUSTOMER</th>
                <th>PRODUCT</th>
                <th>DATE</th>
                <th>AMOUNT</th>
                <th>PAYMENT</th>
                <th>STATUS</th>
                <th>ACTION</th>
            </tr>

            @forelse ($latest_orders as $row)
            <tr>
                <td>#{{ $row['order_id'] }}</td>
                <td>{{ $row['username'] }}</td>
                <td>{!! $row['nama_produk_list'] !!}</td>
                <td>{{ $row['order_date'] }}</td>
                <td>Rp {{ number_format($row['total_price'], 2, ',', '.') }}</td>
                <td>{{ $row['metode_pembayaran'] }}</td>

                <td>
                    <select
                        class="form-select status-select status-{{ strtolower($row['status']) }}"
                        data-order-id="{{ $row['order_id'] }}"
                        data-original-status="{{ strtolower($row['status']) }}"
                    >
                        @php
                            $statuses = ['pending', 'prepared', 'shipped', 'completed'];
                        @endphp

                        @foreach ($statuses as $status)
                            <option value="{{ $status }}"
                                {{ strtolower($row['status']) == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </td>

                <td>
                    <a href="{{ route('admin.order.detail', ['order_id' => $row['order_id']]) }}">
                        <i class='bx bx-show-alt'></i>
                    </a>
                </td>
            </tr>

            @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada order terbaru.</td>
            </tr>
            @endforelse
        </table>

    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const updateStatusUrl = "{{ route('admin.order.updateStatus') }}";

    function updateColor(select, status) {
        select.className = 'form-select status-select';
        select.classList.add('status-' + status);
    }

    document.querySelectorAll('.status-select').forEach(select => {

        updateColor(select, select.value);

        select.addEventListener('change', function () {

            const orderId = this.dataset.orderId;
            const newStatus = this.value;
            const oldStatus = this.dataset.originalStatus;

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
                    this.dataset.originalStatus = newStatus;
                    updateColor(this, newStatus);
                } else {
                    alert('Gagal update status');
                    this.value = oldStatus;
                    updateColor(this, oldStatus);
                }
            })
            .catch(() => {
                alert('Kesalahan jaringan');
                this.value = oldStatus;
                updateColor(this, oldStatus);
            });
        });
    });

});
</script>
@endsection
