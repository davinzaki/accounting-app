<?php

namespace App\Services\Accounting\Journal;

use App\Models\JournalEntry;
use App\Models\Journal;
use App\Models\Account;
use App\Services\FileService;
use App\Actions\Utility\PaginateCollection;

class JournalService
{
    public function getData($request)
    {
        // $search = $request->search;
        // $filter_account = $request->filter_account;
        // $sort_by_code = $request->sort_by_code;

        $query = Journal::query();

        // $query->when(request('search', false), function ($q) use ($search) {
        //     $q->where('name', 'like', '%' . $search . '%');
        // });
        // $query->when(request('filter_account', false), function ($q) use ($filter_account) {
        //     $q->where('account_id', $filter_account);
        // });


        return $query->paginate(10);

    }

    public function getJournalEntries()
    {
        $journalEntries = JournalEntry::get(['id', 'account_id', 'description', 'debit', 'credit',]);

        return $journalEntries;
    }

    public function createData($request){
        $inputs =  $request->only(['description' , 'date', 'amount']);

        if($request->amount === 0) {
            throw new \Exception('Input equivalents value of Debit and Credit');
        }

        $journal = Journal::create($inputs);


        $journalID = $journal->id;
        $journalEntries = $request->input('journal_entries');
        
        foreach($journalEntries as $journal){
              $journalEntry = new JournalEntry();
              $journalEntry->journal_id = $journalID;
              $journalEntry->account_id = intval($journal['account_id']);
              $journalEntry->debit = $journal['debit'];
              $journalEntry->description = $journal['description'];
              $journalEntry->credit = $journal['credit'];
              $journalEntry->save();
        }
  
        return $journal;
     }

    public function deleteData($id)
    {
        $Journal = Journal::findOrFail($id);
        $Journal->delete();

        return $Journal;
    }

    // public function updateData($id, $request)
    // {
    //     // Get Journal Data
    //     $Journal = Journal::findOrFail($id);
    //     $file = $Journal->image;

    //     // Upload the image if the new image exists
    //     if($request->hasFile('image')) {
    //         $fileService = new FileService();
    //         $file = $fileService->uploadFile($request->file('image'));
    //     }

    //     // Update the Journal data
    //     $inputs = $request->only(['date', 'account_id', 'description', 'debit', 'credit']);
    //     $inputs['image'] = $file;
    //     $Journal->update($inputs);

    //     return $Journal;
    // }
}
