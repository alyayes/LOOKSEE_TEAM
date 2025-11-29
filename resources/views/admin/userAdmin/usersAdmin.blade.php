@extends('layouts.mainAdmin') 

@section('title', 'Users List')

@section('styles')
    <style>
        * {
            box-sizing: border-box;
        }

        #myInput {
            /* Sesuaikan URL jika menggunakan asset */
            background-position: 10px 10px;
            background-repeat: no-repeat;
            width: 45%;
            font-size: 16px;
            padding: 12px 20px 12px 40px;
            border: 1px solid #ddd;
            margin-bottom: 12px;
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

        .btn-action {
            width: 40px;
            height: 40px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-right: 5px;
        }
    </style>
</head>
<body>

<div class="propic">
    <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for users..." title="Type in a name" />

    <table id="myTable">
        <tr class="header">
            <th>Username</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
        </tr>

        <tbody>
            {{-- Blade loop untuk menampilkan data. Variabel $users harus dikirim dari Controller. --}}
            @isset($users)
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user['username'] }}</td>
                        <td>{{ $user['name'] }}</td>
                        <td>{{ $user['email'] }}</td>
                        <td>{{ $user['role'] }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4">Tidak ada data pengguna yang tersedia.</td>
                </tr>
            @endisset
        </tbody>
    </table>
</div>

{{-- Inline JavaScript --}}
<script>
    function myFunction() {
        const input = document.getElementById("myInput");
        const filter = input.value.toUpperCase();
        const table = document.getElementById("myTable");
        const tr = table.getElementsByTagName("tr");

        for (let i = 1; i < tr.length; i++) { // Mulai dari 1 untuk melewati baris header
            // Filter berdasarkan kolom 'Name' (indeks 1)
            const td = tr[i].getElementsByTagName("td")[1]; 

            if (td) {
                const txtValue = td.textContent || td.innerText;
                tr[i].style.display = txtValue.toUpperCase().indexOf(filter) > -1 ? "" : "none";
            }
        }
    }
</script>
