@extends('layouts.mainAdmin') 

@section('title', 'Journals List')

@section('styles')
<style>
    .propic {
        margin-left: 0 !important; 
        padding-left: 20px; 
        padding-right: 20px;
        width: 100%; 
        box-sizing: border-box;
    }
    .content-area {
        width: 100%;
        text-align: left; 
    }
    
    * { box-sizing: border-box; }
    #myInput {
        background-image: url('/css/searchicon.png');
        background-position: 10px 10px;
        background-repeat: no-repeat;
        width: 45%;
        min-width: 250px;
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
    #myTable th, #myTable td {
        text-align: left;
        padding: 12px;
        border: 1px solid #ddd;
        word-wrap: break-word;
        max-width: 250px; 
    }
    #myTable tr {
        border-bottom: 1px solid #ddd;
    }
    #myTable tr.header, #myTable tr:hover {
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
    .text-center { text-align: center; }
</style>
@endsection

@section('content')
<div class="propic">
    <div class="content-area">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <section style="margin-top: 0;">
            <button type="button" class="btn btn-primary" onclick="window.location.href='{{ route('stylejournalAdmin.create') }}';">
                <i class='bx bx-plus'></i> Add Journal
            </button>
        </section>

        <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for journal..." title="Type in a name" />

        <table id="myTable">
            <tr class="header">
                <th style="width:8%;">Image</th>
                <th style="width:10%;">Title</th>
                <th style="width:15%;">Description</th>
                <th style="width:20%;">Content</th>
                <th style="width:15%;">Publication Date</th>
                <th style="width:12%;">Action</th>
            </tr>

            <tbody>
                @forelse ($journals as $journal)
                    <tr>
                        <td>
                            <img src="{{ asset('assets/images/journal/' . $journal['image']) }}" 
                                width="60" 
                                height="60" 
                                alt="Gambar Journal">
                        </td>
                        <td>{{ $journal['title'] }}</td>
                        <td>{{ $journal['descr'] }}</td>
                        <td>{{ Illuminate\Support\Str::limit($journal['content'] ?? '', 150) }}</td>
                        
                        <td>{{ date('Y-m-d', strtotime($journal['publication_date'])) }}</td>
                        
                        <td>
                            <a href="{{ route('stylejournalAdmin.edit', $journal['id_journal']) }}" class="btn btn-success btn-action" title="Edit">
                                <i class='bx bx-edit'></i>
                            </a>

                            <form action="{{ route('stylejournalAdmin.destroy', $journal['id_journal']) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-action" title="Hapus" onclick="return confirm('Are you sure you want to delete this journal?')">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No journal data found. Please add a new journal.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        function myFunction() {
            const input = document.getElementById("myInput");
            const filter = input.value.toUpperCase();
            const table = document.getElementById("myTable");
            const tr = table.getElementsByTagName("tr");

            for (let i = 1; i < tr.length; i++) {
                const tdTitle = tr[i].getElementsByTagName("td")[1]; 
                const tdDescr = tr[i].getElementsByTagName("td")[2]; 
                
                let found = false;
                if (tdTitle) {
                    const txtValue = tdTitle.textContent || tdTitle.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                    }
                }
                if (!found && tdDescr) {
                    const txtValue = tdDescr.textContent || tdDescr.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                    }
                }

                tr[i].style.display = found ? "" : "none";
            }
        }
    </script>
</div>
@endsection