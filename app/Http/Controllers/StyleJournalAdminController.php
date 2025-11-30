<?php

namespace App\Http\Controllers;

use App\Models\StyleJournal; // Import model yang baru dibuat
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; // Tetap digunakan untuk operasi file

class StyleJournalAdminController extends Controller
{
    // Hapus fungsi getJournalsData() dan saveJournalsData() karena tidak lagi menggunakan Session/data dummy

    /**
     * Menampilkan daftar semua journal.
     */
    public function index()
    {
        // Ambil semua data dari tabel stylejournal, diurutkan berdasarkan id_journal terbaru
        // Anda bisa menggantinya dengan urutan berdasarkan 'publication_date' jika diinginkan
        $journals = StyleJournal::orderBy('id_journal', 'desc')->get();

        return view('admin.StyleJournalAdmin.stylejournalAdmin', compact('journals')); 
    }

    /**
     * Menampilkan form untuk membuat journal baru.
     */
    public function create()
    {
        return view('admin.StyleJournalAdmin.addStyleJournal');    
    }

    /**
     * Menyimpan journal baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'descr' => 'nullable|string',
            'content' => 'required|string',
            'publication_date' => 'required|date',
            // Pastikan Anda memvalidasi semua jenis file gambar yang diizinkan
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ]);

        $imageName = null;

        // Proses upload file
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // Pastikan Anda menggunakan ekstensi .jpg atau .jpeg, bukan hanya jpg
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads'), $imageName);
        }

        // Buat record baru di database menggunakan Eloquent
        StyleJournal::create([
            'title' => $request->title,
            'descr' => $request->descr,
            'content' => $request->input('content'),
            'publication_date' => $request->publication_date,
            'image' => $imageName,
        ]);

        return redirect()->route('StyleJournalAdmin.stylejournalAdmin')
            ->with('success', 'Journal added successfully!');
    }

    /**
     * Menampilkan form untuk mengedit journal tertentu.
     */
    public function edit($id)
    {
        // Cari journal berdasarkan primary key 'id_journal'
        $journal = StyleJournal::find($id);

        if (!$journal) {
            // Jika journal tidak ditemukan
            return redirect()->route('admin.StyleJournalAdmin.index')->with('error', 'Journal not found.');
        }

        return view('admin.StyleJournalAdmin.editStyleJournal', compact('journal'));
    }

    /**
     * Memperbarui journal tertentu di database.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'descr' => 'nullable|string',
            'content' => 'required|string',
            'publication_date' => 'required|date',
            // 'nullable' karena gambar bisa saja tidak diubah
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ]);

        // Cari journal yang akan diupdate
        $journal = StyleJournal::find($id);

        if (!$journal) {
            return redirect()->route('StyleJournalAdmin.index')->with('error', 'Journal not found for update.');
        }

        $imageName = $journal->image; // Ambil nama gambar yang sudah ada

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($imageName && File::exists(public_path('uploads/' . $imageName))) {
                File::delete(public_path('uploads/' . $imageName));
            }

            // Upload gambar baru
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads'), $imageName);
        }

        // Update record di database
        $journal->update([
            'title' => $request->title,
            'descr' => $request->descr,
            'content' => $request->input('content'),
            'publication_date' => $request->publication_date,
            'image' => $imageName,
        ]);

        // Perhatian: Perlu menyesuaikan rute di bawah jika rute 'admin.stylejournal.index' tidak ada.
        // Asumsi: rute Anda adalah StyleJournalAdmin.index
        return redirect()->route('StyleJournalAdmin.index') 
            ->with('success', 'Journal updated successfully!');
    }

    /**
     * Menghapus journal tertentu dari database.
     */
    public function destroy($id)
    {
        // Cari journal yang akan dihapus
        $journal = StyleJournal::find($id);

        if ($journal) {
            $imageName = $journal->image;

            // Hapus file gambar dari server
            if ($imageName && File::exists(public_path('uploads/' . $imageName))) {
                File::delete(public_path('uploads/' . $imageName));
            }
            
            // Hapus record dari database
            $journal->delete();

            return redirect()->route('StyleJournalAdmin.index')
                ->with('success', 'Journal deleted successfully!');
        }

        return redirect()->route('StyleJournalAdmin.index')
            ->with('error', 'Journal not found for deletion.');
    }
}