@extends('layouts.mainAdmin') 

@section('title', 'Todays Outfit List')

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

        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            display: block;
        }

        .post-image {
            max-width: 100px;
            height: auto;
            display: block;
            margin: auto;
        }
    </style>
</head>

<body>
    <div class="pospic">
        <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Cari postingan..." title="Ketik untuk mencari" />
        
        <div class="table-responsive">
            <table id="myTable">
                <thead>
                    <tr class="header">
                        <th style="width:15%;">Picture Post</th>
                        <th style="width:10%;">Username</th>
                        <th style="width:20%;">Produk Item</th>
                        <th style="width:20%;">Caption</th>
                        <th style="width:15%;">Posting Date</th>
                        <th style="width:10%;">Mood</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Menggunakan @forelse untuk perulangan data dari Controller --}}
                    @forelse ($posts as $post)
                        <tr>
                            <td>
                                @php
                                    // Variabel $uploadWebDir dan $posts dikirim dari TodaysOutfitAdminController
                                    $imageFileName = $post['image_post'] ?? '';
                                    
                                    // Menggunakan helper asset() untuk mendapatkan URL publik yang benar.
                                    if (!empty($imageFileName)) {
                                        $displayImageSrc = asset($uploadWebDir . $imageFileName);
                                    } else {
                                        $displayImageSrc = asset($uploadWebDir . 'default_image.jpg'); 
                                    }
                                @endphp
                                
                                <img src="{{ $displayImageSrc }}"
                                    alt="Gambar Post untuk {{ $post['username'] }}"
                                    class="post-image">
                            </td>
                            <td>{{ $post['username'] ?? 'N/A' }}</td>
                            
                            {{-- Menggunakan {!! !!} (unescaped) agar tag <br> di product_names_list dirender --}}
                            <td>{!! $post['product_names_list'] ?? 'N/A' !!}</td> 
                            
                            <td>{{ $post['caption'] ?? '' }}</td>
                            
                            {{-- Memformat tanggal di Blade --}}
                            <td>{{ date('Y-m-d H:i:s', strtotime($post['created_at'] ?? 'now')) }}</td>
                            
                            <td>{{ $post['mood'] ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        {{-- Blok @empty akan dijalankan jika array $posts kosong --}}
                        <tr>
                            <td colspan="6" style="text-align: center;">Tidak ada post yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Script JavaScript sama dengan sebelumnya --}}
    <script>
        function myFunction() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 1; i < tr.length; i++) { // Start from 1 to skip the header row
                tr[i].style.display = "none"; // Hide by default

                td = tr[i].getElementsByTagName("td");

                // Define columns to search (indices are 0-based)
                // 1: Username, 2: Produk Item, 3: Caption, 4: Posting Date, 5: Mood
                const searchColumns = [1, 2, 3, 4, 5];

                for (let j = 0; j < searchColumns.length; j++) {
                    let colIndex = searchColumns[j];
                    if (td[colIndex]) {
                        txtValue = td[colIndex].textContent || td[colIndex].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = ""; // Show the row
                            break; // Stop searching other columns for this row
                        }
                    }
                }
            }
        }
    </script>
