<?php

namespace Tonypartridge\FilamentNotesAction\Commands;

use Illuminate\Console\Command;

class FilamentNotesActionCommand extends Command
{
    public $signature = 'filament-notes-action';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
