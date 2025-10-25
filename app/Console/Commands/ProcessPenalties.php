<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Penalty;
use App\Models\Transaction;
use Illuminate\Console\Command;

class ProcessPenalties extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-penalties';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
     public function handle()
    {
        $this->info('Checking for overdue transactions...');

        // Find transactions that are borrowed and due date was 7+ days ago
        $overdueTransactions = Transaction::where('transaction_status', 'borrowed')
            ->whereDate('due_date', '<=', Carbon::now()->subDays(7))
            ->get();

        $this->info("Found {$overdueTransactions->count()} overdue transactions.");

        $penaltiesCreated = 0;
        $penaltiesUpdated = 0;

        foreach ($overdueTransactions as $transaction) {
            try {
                // Mark transaction as overdue
                $transaction->markAsOverdue();

                // Calculate penalty details
                $penaltyAmount = $transaction->calculatePenaltyAmount();
                $penaltyReason = $transaction->getPenaltyReason();

                // Check if penalty already exists
                $existingPenalty = Penalty::where('transaction_id', $transaction->id)->first();

                if (!$existingPenalty) {
                    // Create penalty record
                    Penalty::create([
                        'transaction_id' => $transaction->id,
                        'user_id' => $transaction->user_id,
                        'amount' => $penaltyAmount,
                        'reason' => $penaltyReason,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $penaltiesCreated++;
                    $this->info("Created penalty for Transaction #{$transaction->id} - Amount: ₱{$penaltyAmount}");
                } else {
                    // Update existing penalty with new amount and reason
                    $existingPenalty->update([
                        'amount' => $penaltyAmount,
                        'reason' => $penaltyReason,
                        'updated_at' => now(),
                    ]);

                    $penaltiesUpdated++;
                    $this->info("Updated penalty for Transaction #{$transaction->id} - New Amount: ₱{$penaltyAmount}");
                }

            } catch (\Exception $e) {
                $this->error("Error processing transaction #{$transaction->id}: " . $e->getMessage());
            }
        }

        $this->info("Overdue transaction check completed. Created: {$penaltiesCreated}, Updated: {$penaltiesUpdated}");
        
        return Command::SUCCESS;
    }
}
