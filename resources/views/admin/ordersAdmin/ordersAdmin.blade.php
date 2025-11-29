@extends('layouts.mainAdmin')

@section('title', 'Orders List')

@section('styles')
    <style>
        * {
            box-sizing: border-box;
        }

        #myInput {
            background-image: url('/css/searchicon.png');
            background-position: 10px 10px;
            background-repeat: no-repeat;
            width: 45%;
            font-size: 16px;
            padding: 12px 20px 12px 40px;
            border: 1px solid #ddd;
            margin-bottom: 5px;
            margin-top: 20px;
        }

        #myTable {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #ddd;
            font-size: 18px;
            margin-bottom: 50px;
        }

        #myTable th,
        #myTable td {
            text-align: left;
            padding: 12px;
            border: 1px solid #ddd;
        }

        #myTable tr {
            border-bottom: 1px solid #ddd;
        }

        #myTable tr.header,
        #myTable tr:hover {
            background-color: rgb(255, 234, 247);
        }

        .status-container {
            position: relative;
            display: inline-block;
        }

        .status-badge {
            display: inline-block;
            padding: .35em .65em;
            font-size: .75em;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            border-radius: .375rem;
            cursor: pointer;
            transition: background-color .15s ease-in-out, color .15s ease-in-out;
        }

        .status-badge.pending {
            background-color: rgba(255, 193, 7, .8);
            color: #fff;
        }

        .status-badge.prepared {
            background-color: rgb(253, 152, 0);
            color: #fff;
        }

        .status-badge.shipped {
            background-color: rgb(148, 190, 253);
            color: #fff;
        }

        .status-badge.completed {
            background-color: rgb(60, 199, 134);
            color: #fff;
        }

        .custom-dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1000;
            min-width: 10rem;
            padding: .5rem 0;
            margin: .125rem 0 0;
            font-size: 1rem;
            color: #212529;
            text-align: left;
            list-style: none;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid rgba(0, 0, 0, .15);
            border-radius: .25rem;
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .175);
        }

        .custom-dropdown-menu.show {
            display: block;
        }

        .custom-dropdown-item {
            display: block;
            padding: .25rem 1rem;
            clear: both;
            font-weight: 400;
            color: #212529;
            text-align: inherit;
            text-decoration: none;
            white-space: nowrap;
            background-color: transparent;
            border: 0;
            cursor: pointer;
        }

        .custom-dropdown-item:hover,
        .custom-dropdown-item:focus {
            color: #1e2125;
            background-color: #e9ecef;
        }

        .custom-dropdown-item.active-option {
            background-color: rgb(255, 161, 200);
            color: #fff;
        }
    </style>
    </head>

    <body>

        <div class="propic">
            <section>{{-- Konten tambahan --}}</section>
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

                {{-- Menggunakan Blade @forelse untuk looping data --}}
                @forelse ($orders as $row)
                    @php
                        $status_lower = strtolower($row['status']);
                        $bank_ewallet_info = '-';
                        if ($row['metode_pembayaran'] == 'Bank Transfer') {
                            $bank_ewallet_info = $row['bank_name'] ?? '-';
                        } elseif ($row['metode_pembayaran'] == 'E-Wallet') {
                            $bank_ewallet_info = $row['ewallet_provider_name'] ?? '-';
                        }
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
                                    <a class="custom-dropdown-item {{ $status_lower == 'pending' ? 'active-option' : '' }}"
                                        href="#" data-status="pending">Pending</a>
                                    <a class="custom-dropdown-item {{ $status_lower == 'prepared' ? 'active-option' : '' }}"
                                        href="#" data-status="prepared">Prepared</a>
                                    <a class="custom-dropdown-item {{ $status_lower == 'shipped' ? 'active-option' : '' }}"
                                        href="#" data-status="shipped">Shipped</a>
                                    <a class="custom-dropdown-item {{ $status_lower == 'completed' ? 'active-option' : '' }}"
                                        href="#" data-status="completed">Completed</a>
                                </div>
                            </div>
                        </td>
                        {{-- Menggunakan Laravel Route Helper untuk URL --}}
                        <td><a href="{{ route('admin.order.detail', ['order_id' => $row['order_id']]) }}"><i
                                    class='bx bx-show-alt'></i></a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">Tidak ada data</td>
                    </tr>
                @endforelse
            </table>

            <script>
                // Fungsi pencarian tetap sama
                function myFunction() {
                    var input, filter, table, tr, td, i, j, txtValue, found;
                    input = document.getElementById("myInput");
                    filter = input.value.toUpperCase();
                    table = document.getElementById("myTable");
                    tr = table.getElementsByTagName("tr");

                    for (i = 1; i < tr.length; i++) {
                        found = false;
                        td = tr[i].getElementsByTagName("td");
                        const searchableColumns = [0, 1, 2, 5, 6, 7];

                        for (j = 0; j < td.length; j++) {
                            if (searchableColumns.includes(j) && td[j]) {
                                txtValue = td[j].textContent || td[j].innerText;
                                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                    found = true;
                                    break;
                                }
                            }
                        }
                        tr[i].style.display = found ? "" : "none";
                    }
                }

                document.addEventListener('DOMContentLoaded', function() {
                    // Mengambil token CSRF dari meta tag atau input tersembunyi
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    // Menggunakan route helper untuk mendapatkan URL yang benar
                    const updateStatusUrl = '{{ route('orders.update_status') }}';
                    // Menangani klik pada badge
                    document.querySelectorAll('.status-badge').forEach(badge => {
                        badge.addEventListener('click', function(event) {
                            event.stopPropagation();
                            const orderId = this.dataset.orderId;
                            const dropdownMenu = document.getElementById('dropdown-' + orderId);

                            document.querySelectorAll('.custom-dropdown-menu').forEach(menu => {
                                if (menu !== dropdownMenu) {
                                    menu.classList.remove('show');
                                }
                            });

                            dropdownMenu.classList.toggle('show');
                        });
                    });

                    // Menangani klik pada item di dropdown kustom (AJAX)
                    document.querySelectorAll('.custom-dropdown-item').forEach(item => {
                        item.addEventListener('click', function(event) {
                            event.preventDefault();
                            event.stopPropagation();

                            const newStatus = this.dataset.status;
                            const dropdownMenu = this.closest('.custom-dropdown-menu');
                            const orderId = dropdownMenu.id.replace('dropdown-', '');

                            const badgeElement = document.querySelector(
                                `.status-badge[data-order-id="${orderId}"]`);

                            // Kirim permintaan AJAX menggunakan Fetch API
                            fetch(updateStatusUrl, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken // Kirim token CSRF
                                    },
                                    body: JSON.stringify({
                                        order_id: orderId,
                                        status: newStatus
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // Update tampilan jika sukses
                                        if (badgeElement) {
                                            badgeElement.classList.remove('pending', 'prepared',
                                                'shipped', 'completed');
                                            badgeElement.classList.add(newStatus);
                                            badgeElement.innerHTML = newStatus.charAt(0).toUpperCase() +
                                                newStatus.slice(1) +
                                                ' <i class="fa fa-caret-down"></i>';
                                        }

                                        // Perbarui kelas 'active-option' pada item dropdown
                                        dropdownMenu.querySelectorAll('.custom-dropdown-item').forEach(
                                            option => {
                                                option.classList.remove('active-option');
                                            });
                                        this.classList.add('active-option');

                                        dropdownMenu.classList.remove('show');
                                        alert(data.message);
                                    } else {
                                        alert('Gagal memperbarui status: ' + data.message);
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('Terjadi kesalahan jaringan atau server.');
                                });
                        });
                    });

                    // Menutup dropdown saat klik di luar
                    document.addEventListener('click', function(event) {
                        document.querySelectorAll('.custom-dropdown-menu').forEach(menu => {
                            menu.classList.remove('show');
                        });
                    });
                });
            </script>
        </div>
    </body>

    </html>
