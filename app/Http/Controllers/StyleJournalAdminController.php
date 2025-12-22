<?php

namespace App\Http\Controllers;

use App\Models\StyleJournal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Carbon\Carbon; 

class StyleJournalAdminController extends Controller
{
    private $uploadPath = 'assets/images/journal';

    public function index()
    {
        $journals = StyleJournal::orderBy('publication_date', 'desc')->get();
        return view('admin.StyleJournalAdmin.stylejournalAdmin', compact('journals'));
    }

    public function create()
    {
        return view('admin.StyleJournalAdmin.addStyleJournal');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'descr' => 'required|string',
            'content' => 'required|string',
            'publication_date' => 'required|date',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
 
        $imageName = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path($this->uploadPath), $imageName);
        }
        
        $publicationDate = Carbon::parse($request->publication_date);

        StyleJournal::create([
            'title' => $request->title,
            'descr' => $request->descr,
            'content' => $request->input('content'),
            'publication_date' => $publicationDate,
            'image' => $imageName,
        ]);

        return redirect()->route('stylejournalAdmin.index')
            ->with('success', 'Journal added successfully!');
    }

    public function edit($id)
    {
        $journal = StyleJournal::find($id);

        if (!$journal) {
            return redirect()->route('stylejournalAdmin.index')->with('error', 'Journal not found.');
        }

        return view('admin.StyleJournalAdmin.editStyleJournal', compact('journal'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'descr' => 'nullable|string',
            'content' => 'required|string',
            'publication_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $journal = StyleJournal::find($id);

        if (!$journal) {
            return redirect()->route('stylejournalAdmin.index')->with('error', 'Journal not found for update.');
        }

        $imageName = $journal->image;

        if ($request->hasFile('image')) {
            if ($imageName && File::exists(public_path($this->uploadPath . '/' . $imageName))) {
                File::delete(public_path($this->uploadPath . '/' . $imageName));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path($this->uploadPath), $imageName);
        }
        
        $publicationDate = Carbon::parse($request->publication_date);

        $journal->update([
            'title' => $request->title,
            'descr' => $request->descr,
            'content' => $request->input('content'),
            'publication_date' => $publicationDate,
            'image' => $imageName,
        ]);

        return redirect()->route('stylejournalAdmin.index')
            ->with('success', 'Journal updated successfully!');
    }

    public function destroy($id)
    {
        $journal = StyleJournal::find($id);

        if ($journal) {
            $imageName = $journal->image;

            if ($imageName && File::exists(public_path($this->uploadPath . '/' . $imageName))) {
                File::delete(public_path($this->uploadPath . '/' . $imageName));
            }

            $journal->delete();

            return redirect()->route('stylejournalAdmin.index')
                ->with('success', 'Journal deleted successfully!');
        }

        return redirect()->route('stylejournalAdmin.index')
            ->with('error', 'Journal not found for deletion.');
    }
}