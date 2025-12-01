@extends('layouts.mainAdmin') 

@section('title', 'Dashboard')

@section('styles')
    <style>
        {
            box-sizing: border-box;
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

        .status-container {
            position: relative;
            display: inline-block;
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
    <div class="page-wrapper">
        <div class="page-content">
            {{-- Bagian Card Dashboard --}}
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
                {{-- Card 1: Total Users --}}
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-4 border-info">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h3 class="my-1 text-info">{{ number_format($user_count, 0, ',', '.') }}</h3>
                                    <p class="mb-0 text-secondary">Total Users</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto">
                                    <i class='bx bxs-user-circle'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Total Products --}}
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-4 border-danger">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h3 class="my-1 text-danger">{{ number_format($product_count, 0, ',', '.') }}</h3>
                                    <p class="mb-0 text-secondary">Total Products</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto">
                                    <i class='bx bx-package'></i> </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 3: Total Orders --}}
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-4 border-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h3 class="my-1 text-success">{{ number_format($order_count, 0, ',', '.') }}</h3>
                                    <p class="mb-0 text-secondary">Total Orders</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                                    <i class='bx bx-line-chart'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 4: Total Sales --}}
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-4 border-warning">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    {{-- number_format digunakan untuk format Rupiah --}}
                                    <h3 class="my-1 text-warning">Rp {{ number_format($total_sales, 2, ',', '.') }}</h3>
                                    <p class="mb-0 text-secondary">Total Sales</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto">
                                    <i class='bx bx-dollar-circle'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            <h5>Recent Orders</h5>

            <table id="myTable">
                <tr class="header">
                    <th style="width:10%;">ORDER ID</th>
                    <th style="width:15%;">CUSTOMER</th>
                    <th style="width:20%;">PRODUCT</th>
                    <th style="width:15%;">ORDER DATE</th>
                    <th style="width:12%;">AMOUNT</th>
                    <th style="width:13%;">PAYMENT METHOD</th>
                    <th style="width:10%;">STATUS</th>
                    <th style="width:5%;">ACTION</th>
                </tr>

                {{-- Menggunakan Blade @forelse untuk perulangan order --}}
                @forelse ($latest_orders as $row)
                    <tr>
                        <td>#{{ $row["order_id"] }}</td>
                        <td>{{ $row["username"] }}</td>
                        {{-- {!! !!} digunakan karena 'nama_produk_list' mengandung tag <br> --}}
                        <td>{!! $row["nama_produk_list"] !!}</td>
                        <td>{{ $row["order_date"] }}</td>
                        <td>Rp {{ number_format($row["total_price"], 2, ',', '.') }}</td>
                        <td>{{ $row["metode_pembayaran"] }}</td>
                        <td>
                            <div class="status-container">
                                <span class="status-badge {{ strtolower($row['status']) }}"
                                      data-order-id="{{ $row['order_id'] }}">
                                    {{ ucfirst($row['status']) }} <i class="fa fa-caret-down"></i>
                                </span>

                                <div class="custom-dropdown-menu" id="dropdown-{{ $row['order_id'] }}">
                                    @php
                                        $statuses = ['pending', 'prepared', 'shipped', 'completed'];
                                        $currentStatus = strtolower($row['status']);
                                    @endphp
                                    @foreach ($statuses as $status)
                                        <a class="custom-dropdown-item {{ ($currentStatus == $status) ? 'active-option' : '' }}"
                                            href="#" data-status="{{ $status }}">
                                            {{ ucfirst($status) }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </td>
                        {{-- Menggunakan helper route() untuk link detail order --}}
                        <td><a href="{{ url('order_detail/' . $row['order_id']) }}"><i class='bx bx-show-alt'></i></a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">Tidak ada data order terbaru yang tersedia.</td>
                    </tr>
                @endforelse
            </table>

            {{-- --- JAVASCRIPT LOGIC --- --}}
            <script>
                // Fungsi pencarian JavaScript
                function myFunction() {
                    var input, filter, table, tr, td, i, j, txtValue, found;
                    input = document.getElementById("myInput");
                    filter = input.value.toUpperCase();
                    table = document.getElementById("myTable");
                    tr = table.getElementsByTagName("tr");

                    for (i = 1; i < tr.length; i++) { // Mulai dari 1 untuk melewatkan header
                        found = false;
                        td = tr[i].getElementsByTagName("td");
                        const searchableColumns = [0, 1, 2, 5, 6];

                        for (j = 0; j < td.length; j++) {
                            if (searchableColumns.includes(j) && td[j]) {
                                // Ambil teks dari badge status untuk pencarian kolom Status
                                let cellText = td[j].querySelector('.status-badge') ? td[j].querySelector('.status-badge').textContent.replace(' ▾', '').trim() : td[j].textContent || td[j].innerText;
                                txtValue = cellText;
                                
                                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                    found = true;
                                    break;
                                }
                            }
                        }
                        tr[i].style.display = found ? "" : "none";
                    }
                }

                document.addEventListener('DOMContentLoaded', function () {
                    // Ambil CSRF token dari meta tag
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    // URL untuk AJAX update status (Pastikan route ini sudah didefinisikan)
                    const updateStatusUrl = '{{ route('orders.update_status') }}'; 

                    // 1. Toggle Dropdown Status
                    document.querySelectorAll('.status-badge').forEach(badge => {
                        badge.addEventListener('click', function (event) {
                            event.stopPropagation();
                            const orderId = this.dataset.orderId;
                            const dropdownMenu = document.getElementById('dropdown-' + orderId);

                            // Sembunyikan semua dropdown lain
                            document.querySelectorAll('.custom-dropdown-menu').forEach(menu => {
                                if (menu !== dropdownMenu) {
                                    menu.classList.remove('show');
                                }
                            });

                            dropdownMenu.classList.toggle('show');
                        });
                    });

                    // 2. Update Status via AJAX
                    document.querySelectorAll('.custom-dropdown-item').forEach(item => {
                        item.addEventListener('click', function (event) {
                            event.preventDefault();
                            event.stopPropagation();

                            const newStatus = this.dataset.status;
                            const dropdownMenu = this.closest('.custom-dropdown-menu');
                            const orderId = dropdownMenu.id.replace('dropdown-', '');
                            const badgeElement = document.querySelector(`.status-badge[data-order-id="${orderId}"]`);

                            // Update tampilan badge di frontend
                            if (badgeElement) {
                                badgeElement.classList.remove('pending', 'prepared', 'shipped', 'completed');
                                badgeElement.classList.add(newStatus);
                                badgeElement.innerHTML = newStatus.charAt(0).toUpperCase() + newStatus.slice(1) + ' <i class="fa fa-caret-down"></i>';
                            }

                            // Update kelas 'active-option'
                            dropdownMenu.querySelectorAll('.custom-dropdown-item').forEach(option => {
                                option.classList.remove('active-option');
                            });
                            this.classList.add('active-option');

                            dropdownMenu.classList.remove('show');

                            // Kirim data ke Controller (Simulasi update database)
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
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Pesan sukses dari Controller (mengandung kata SIMULASI)
                                    alert('✅ ' + data.message); 
                                } else {
                                    alert('❌ Gagal memperbarui status: ' + data.message);
                                }
                            })
                            .catch(error => {
                                console.error('Error saat melakukan fetch:', error);
                                alert('Terjadi kesalahan jaringan atau server.');
                            });
                        });
                    });

                    // 3. Sembunyikan dropdown jika klik di luar
                    document.addEventListener('click', function (event) {
                        document.querySelectorAll('.custom-dropdown-menu').forEach(menu => {
                            if (!menu.contains(event.target) && !event.target.closest('.status-badge')) {
                                menu.classList.remove('show');
                            }
                        });
                    });
                });
            </script>
        </div>
    </div>
</body>
</html>