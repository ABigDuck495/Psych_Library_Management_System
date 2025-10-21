<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Thesis;
use App\Models\Transaction;
use App\Models\User;

class DebugRequestThesis extends Command
{
    protected $signature = 'debug:request-thesis {thesisId} {userId?}';
    protected $description = 'Simulate a thesis request for debugging';

    public function handle()
    {
        $thesisId = $this->argument('thesisId');
        $userId = $this->argument('userId') ?: 1;

        $thesis = Thesis::find($thesisId);
        if (!$thesis) {
            $this->error('Thesis not found: ' . $thesisId);
            return 1;
        }

        $user = User::find($userId);
        if (!$user) {
            $this->error('User not found: ' . $userId);
            return 1;
        }

        $this->info('Thesis: ' . $thesis->id . ' - ' . $thesis->title);
        $this->info('User: ' . $user->id . ' - ' . $user->email);

        if (!$thesis->canBeRequested()) {
            $this->warn('Thesis cannot be requested (no available copies).');
            return 0;
        }

        $availableCopy = $thesis->getNextAvailableCopy();
        if (!$availableCopy) {
            $this->warn('No available copy returned by getNextAvailableCopy().');
            return 0;
        }

        $this->info('Using copy id: ' . $availableCopy->id . ' (is_available=' . ($availableCopy->is_available?1:0) . ')');

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'copy_id' => $availableCopy->id,
            'borrow_date' => now(),
            'due_date' => now()->addDays(14),
            'return_date' => null,
            'transaction_status' => 'requested',
        ]);

        $availableCopy->is_available = false;
        $availableCopy->save();

        $this->info('Transaction created id: ' . $transaction->id . '. Copy marked unavailable.');
        return 0;
    }
}
