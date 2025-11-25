<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;

class StyleJournalAdminController extends Controller
{
    private function getJournalsData()
    {
        return Session::get('journals', [
            [
                'id_journal' => 1,
                'title' => '10 Tips for Effortless Everyday Fashion',
                'descr' => 'Discover simple yet effective ways to elevate your...',
                'content' => 'Invest in Quality Basics Investasi pada basio i...',
                'publication_date' => '2025-05-31 11:31:54',
                'image' => '1d.jpg'
            ],
            [
                'id_journal' => 2,
                'title' => 'Effortless Styling Tips for Every Occasion',
                'descr' => 'Dress flawlessly for any event with these simple w...',
                'content' => 'Permhakan Anda merasa kesulitan dalam memilih paka...',
                'publication_date' => '2025-06-02 14:48:58',
                'image' => '2d.jpg' 
            ],
            [
                'id_journal' => 3,
                'title' => 'Style Guides for Every Occasion',
                'descr' => 'From casual outings to formal events, find outfit ...',
                'content' => 'Dalam hiruk pikuk kehidupan modern, menemukan outfi...',
                'publication_date' => '2025-03-28 18:17:01',
                'image' => '3d.jpg'
            ],
            [
                'id_journal' => 4,
                'title' => 'The Power of Perfect Color Harmony',
                'descr' => 'Unlock the secrets to creating visually appealing...',
                'content' => 'Permhakan Anda melihat seseorang dengan outfit yan...',
                'publication_date' => '2025-06-04 16:18:16',
                'image' => '4d.jpg'
            ],
            [
                'id_journal' => 5,
                'title' => 'Wardrobe Hacks for Any Event',
                'descr' => 'From brunch to dinner, these wardrobe hacks ensure...',
                'content' => 'Apakah Anda pernah merasa panik saat harus memilih...',
                'publication_date' => '2025-04-10 16:19:24',
                'image' => '5d.jpg'
            ],
            [
                'id_journal' => 6,
                'title' => 'Mastering Color Combinations',
                'descr' => 'Learn the art of pairing colors to create stunning...',
                'content' => 'Apakah Anda pernah mengagumi seseorang yang selalu...',
                'publication_date' => '2025-05-17 18:15:15',
                'image' => '6d.jpg'
            ],
            [
                'id_journal' => 7,
                'title' => 'Mastering Monochrome: Simplicity in Style',
                'descr' => 'Elevate your wardrobe with monochrome outfits that...',
                'content' => 'Dalam dunia fashion yang terus bergerak cepat deng...',
                'publication_date' => '2025-05-31 18:00:00',
                'image' => '7d.jpg'
            ],
            [
                'id_journal' => 8,
                'title' => 'Sustainable Fashion Choices You Can Make Today',
                'descr' => 'Embrace eco-friendly fashion with these sustainabl...',
                'content' => 'Industri fashion adalah salah satu penyumbang terb...',
                'publication_date' => '2025-04-16 16:14:02',
                'image' => '8d.jpg'
            ],
            [
                'id_journal' => 9,
                'title' => 'Essential Wardrobe Pieces for Year-Round Style',
                'descr' => 'Discover the key pieces you need for a versatile w...',
                'content' => 'Permhakan Anda membuka lemari pakaian dan merasa t...',
                'publication_date' => '2025-06-15 15:28:22',
                'image' => '9d.jpg'
            ],
            [
                'id_journal' => 10,
                'title' => 'Accessorizing 101: How to Enhance Any Outfit',
                'descr' => 'Learn how to use accessories to add a personal tou...',
                'content' => 'Permhakan Anda merasa bahwa ada sesuatu yang hilan...',
                'publication_date' => '2025-06-04 15:27:07',
                'image' => '10d.jpg'
            ],
            [
                'id_journal' => 11,
                'title' => 'The Art of Layering: Stay Warm and Stylish',
                'descr' => 'Master the technique of layering your clothes to s...',
                'content' => 'Ketika suhu mulai menurun atau saat Anda berada di...',
                'publication_date' => '2025-06-02 15:26:49',
                'image' => '11d.jpg'
            ],
            [
                'id_journal' => 12,
                'title' => 'Footwear Trends to Watch This Year',
                'descr' => 'Step up your shoe game with the latest trends in f...',
                'content' => 'Dunia fashion terus berputar, dan begitu pula deng...',
                'publication_date' => '2025-06-09 15:23:58',
                'image' => '12d.jpg'
            ],
        ]);
    }

    private function saveJournalsData($journals)
    {
        Session::put('journals', $journals);
    }

    public function index()
    {
        $journals = $this->getJournalsData();

        $journals = collect($journals);

        return view('StyleJournalAdmin.stylejournalAdmin', compact('journals')); 
    }

    public function create()
    {
        return view('StyleJournalAdmin.addStyleJournal');    
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'descr' => 'nullable|string',
            'content' => 'required|string',
            'publication_date' => 'required|date',
            'image' => 'required|image|mimes:jpg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads'), $imageName);
        }

        $journals = $this->getJournalsData();

        $newId = empty($journals) ? 1 : max(array_column($journals, 'id_journal')) + 1;

        $newJournal = [
            'id_journal' => $newId,
            'title' => $request->title,
            'descr' => $request->descr,
            'content' => $request->input('content'),
            'publication_date' => $request->publication_date,
            'image' => $imageName,
        ];

        array_unshift($journals, $newJournal);
        $this->saveJournalsData($journals);

        return redirect()->route('StyleJournalAdmin.stylejournalAdmin')
            ->with('success', 'Journal added successfully!');
    }

    public function edit($id)
    {
        $journals = $this->getJournalsData();
        $journalId = (int) $id;

        $journal = collect($journals)->firstWhere('id_journal', $journalId);

        if (!$journal) {
            return redirect()->route('StyleJournalAdmin.index')->with('error', 'Journal not found.');
        }

        return view('StyleJournalAdmin.editStyleJournal', compact('journal'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'descr' => 'nullable|string',
            'content' => 'required|string',
            'publication_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpg,png,jpg,gif,svg|max:2048',
        ]);

        $journals = $this->getJournalsData();
        $journalId = (int) $id;
        $journalIndex = null;

        foreach ($journals as $index => $item) {
            if ($item['id_journal'] === $journalId) {
                $journalIndex = $index;
                break;
            }
        }

        if (is_null($journalIndex)) {
            return redirect()->route('StyleJournalAdmin.index')->with('error', 'Journal not found for update.');
        }

        $currentJournal = $journals[$journalIndex];
        $imageName = $currentJournal['image'];

        if ($request->hasFile('image')) {
            if ($imageName && File::exists(public_path('uploads/' . $imageName))) {
                File::delete(public_path('uploads/' . $imageName));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads'), $imageName);
        }

        $journals[$journalIndex] = [
            'id_journal' => $journalId,
            'title' => $request->title,
            'descr' => $request->descr,
            'content' => $request->input('content'),
            'publication_date' => $request->publication_date,
            'image' => $imageName,
        ];

        $this->saveJournalsData($journals);

        return redirect()->route('admin.stylejournal.index')
            ->with('success', 'Journal updated successfully!');
    }

    public function destroy($id)
    {
        $journals = $this->getJournalsData();
        $journalId = (int) $id;

        $keyToRemove = null;
        $imageName = null;

        foreach ($journals as $index => $item) {
            if ($item['id_journal'] === $journalId) {
                $keyToRemove = $index;
                $imageName = $item['image'];
                break;
            }
        }

        if (!is_null($keyToRemove)) {
            if ($imageName && File::exists(public_path('uploads/' . $imageName))) {
                File::delete(public_path('uploads/' . $imageName));
            }
            
            unset($journals[$keyToRemove]);

            $this->saveJournalsData(array_values($journals)); 

            return redirect()->route('StyleJournalAdmin.index')
                ->with('success', 'Journal deleted successfully!');
        }

        return redirect()->route('StyleJournalAdmin.index')
            ->with('error', 'Journal not found for deletion.');
    }
}