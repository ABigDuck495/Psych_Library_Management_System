<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Thesis;

class DebugThesisAvailable extends Command
{
    protected $signature = 'debug:thesis-available {thesisId?}';
    protected $description = 'Print next available ThesisCopy for a thesis (for debugging)';

    public function handle()
    {
        $id = $this->argument('thesisId');
        $thesis = $id ? Thesis::find($id) : Thesis::first();
        if (!$thesis) {
            $this->error('No thesis found.');
            return 1;
        }
        $this->info('Thesis ID: ' . $thesis->id . ' Title: ' . $thesis->title);
        $copy = $thesis->getNextAvailableCopy();
        if (!$copy) {
            $this->warn('No available copy found.');
            return 0;
        }
        $this->info('Found copy ID: ' . $copy->id . ' is_available: ' . ($copy->is_available ? '1' : '0'));
        return 0;
    }
}
