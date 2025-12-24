@extends('layouts.mainAdmin')

@section('title', 'Dashboard Admin')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/dashboardAdmin.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
/* CSS Tambahan untuk Tabel */
* { box-sizing: border-box; }

#myTable {
    border-collapse: collapse;
    width: 100%;
    border: 1px solid #ddd;
    font-size: 15px;
    background-color: #fff;
}

#myTable th, #myTable td {
    padding: 10px;
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

.status-select {
    font-weight: 600;
    cursor: pointer;
}

/* Warna Status */
.status-pending { background-color: #ffc107; border-color: #ffc107; color: #000; }
.status-prepared { background-color: #17a2b8; border-color: #17a2b8; color: white; }
.status-shipped { background-color: #007bff; border-color: #007bff; color: white; }
.status-completed { background-color: #28a745; border-color: #28a745; color: white; }

/* Style tambahan untuk Icon di Card */
.widgets-icons-2 {
    width: 56px;
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #ededed;
    font-size: 27px;
    border-radius: 50%;
}

/* Gradient Backgrounds (Penting agar icon berwarna) */
.bg-gradient-scooter { background: linear-gradient(to right, #17ead9, #6078ea); }
.bg-gradient-bloody { background: linear-gradient(to right, #f54ea2, #ff7676); }
.bg-gradient-ohhappiness { background: linear-gradient(to right, #00b09b, #96c93d); }
.bg-gradient-blooker { background: linear-gradient(to right, #ffdf40, #ff8359); }
</style>
@endsection

@section('content')

    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 mb-4">

        <div class="col">
            <div class="card radius-10 border-start border-info border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Users</p>
                            <h4 class="my-1 text-info">{{ number_format($user_count) }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto">
                            <i class='bx bxs-group'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card radius-10 border-start border-danger border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Products</p>
                            <h4 class="my-1 text-danger">{{ number_format($product_count) }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto">
                            <i class='bx bxs-shopping-bag'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card radius-10 border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Orders</p>
                            <h4 class="my-1 text-success">{{ number_format($order_count) }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                            <i class='bx bxs-cart-add'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card radius-10 border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Sales</p>
                            <h4 class="my-1 text-warning">
                                Rp {{ number_format($total_sales, 2, ',', '.') }}
                            </h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-blooker text-white ms-auto">
                            <i class='bx bxs-wallet'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="card radius-10">
        <div class="card-body">
            <h5 class="mb-3">Recent Orders</h5>
            <div class="table-responsive">
                <table id="myTable" class="table table-bordered">
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

                        <td class="text-center">
                            <a href="{{ route('admin.order.detail', ['order_id' => $row['order_id']]) }}" class="btn btn-sm btn-light border">
                                <i class='bx bx-show-alt'></i>
                            </a>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">Tidak ada order terbaru.</td>
                    </tr>
                    @endforelse
                </table>
            </div>
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

            this.disabled = true;

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
                this.disabled = false;
                if (data.success) {
                    this.dataset.originalStatus = newStatus;
                    updateColor(this, newStatus);
                } else {
                    alert('Gagal update status: ' + (data.message || 'Error'));
                    this.value = oldStatus;
                    updateColor(this, oldStatus);
                }
            })
            .catch((err) => {
                console.error(err);
                this.disabled = false;
                alert('Kesalahan jaringan atau server');
                this.value = oldStatus;
                updateColor(this, oldStatus);
            });
        });
    });

});
</script>
@endsection