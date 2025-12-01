<?php

namespace App\Http\Controllers;

use App\Models\StyleJournal;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StyleJournalController extends Controller
{
    private function format_tanggal($date_string) {
        if (empty($date_string)) return '';

        $carbonDate = Carbon::parse($date_string)->locale('id');
        return $carbonDate->translatedFormat('d F Y');
    }

    public function index()
    {
        $journals = StyleJournal::orderBy('publication_date', 'desc')->paginate(6);
        
        $journals->getCollection()->transform(function ($journal) {
            $journal->formatted_date = $this->format_tanggal($journal->publication_date);
            return $journal;
        });

        return view('stylejournal.index', compact('journals'));
    }
    
    public function show($id)
    {
        $journal = StyleJournal::findOrFail($id);

        $journal->formatted_date = $this->format_tanggal($journal->publication_date);

        return view('stylejournal.show', compact('journal'));
    }
}