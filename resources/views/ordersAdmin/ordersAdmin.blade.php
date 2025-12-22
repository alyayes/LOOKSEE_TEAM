@extends('layouts.mainAdmin')

<link rel="stylesheet" href="{{ asset('assets/css/dashboardAdmin.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('title', 'Orders List')

@section('styles')
<style>
* { box-sizing: border-box; }

#myTable {
    border-collapse: collapse;
    width: 100%;
    border: 1px solid #ddd;
    font-size: 18px;
    margin-bottom: 50px;
}

#myTable th, #myTable td {
    text-align: left;
    padding: 12px;
    border: 1px solid #ddd;
}

#myTable tr.header, #myTable tr:hover {
    background-color: rgb(255, 234, 247);
}

.status-pending { background-color: #ffc107; color: #333; }
.status-prepared { background-color: #17a2b8; color: #fff; }
.status-shipped { background-color: #007bff; color: #fff; }
.status-completed { background-color: #28a745; color: #fff; }
</style>
@endsection

@section('content')
<div class="page-wrapper">
    <div class="page-content">

        <h5>Orders List</h5>

        <table id="myTable">
            <tr class="header">
                <th>ORDER ID</th>
                <th>CUSTOMER</th>
                <th>PRODUCT</th>
                <th>ORDER DATE</th>
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

                <!-- STATUS DROPDOWN -->
                <td>
                    <select
                        class="form-select status-select status-{{ strtolower($row['status']) }}"
                        data-order-id="{{ $row['order_id'] }}"
                        data-original-status="{{ strtolower($row['status']) }}"
                    >
                        @php $statuses = ['pending', 'prepared', 'shipped', 'completed']; @endphp
                        @foreach ($statuses as $s)
                            <option value="{{ $s }}" {{ strtolower($row['status']) == $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                </td>

                <!-- ACTION -->
                <td>
                    <a href="{{ route('admin.order.detail', ['order_id' => $row['order_id']]) }}">
                        <i class='bx bx-show-alt'></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data order terbaru.</td>
            </tr>
            @endforelse
        </table>

    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const updateStatusUrl = "{{ route('admin.order.updateStatus') }}";

    function updateSelectColor(element, status){
        element.className = 'form-select status-select';
        element.classList.add('status-' + status);
    }

    document.querySelectorAll('.status-select').forEach(select => {

        updateSelectColor(select, select.value); 

        select.addEventListener('change', function(){

            const orderId = this.dataset.orderId;
            const newStatus = this.value;
            const originalStatus = this.dataset.originalStatus;

            fetch(updateStatusUrl, {
                method: 'POST',
                headers: {
                    'Content-Type':'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    order_id: orderId,
                    status: newStatus
                })
            })
            .then(res => res.json())
            .then(data => {

                if(data.success){
                    this.dataset.originalStatus = newStatus;
                    updateSelectColor(this, newStatus);
                } else {
                    alert('Gagal update status!');
                    this.value = originalStatus;
                    updateSelectColor(this, originalStatus);
                }

            })
            .catch(() => {
                alert('Terjadi kesalahan jaringan');
                this.value = originalStatus;
                updateSelectColor(this, originalStatus);
            });

        });

    });

});
</script>
@endsection