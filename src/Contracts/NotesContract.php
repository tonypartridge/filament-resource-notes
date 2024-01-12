<?php

namespace Tonypartridge\FilamentNotesAction\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface NotesContract
{
    public function filament_notes(): MorphMany;
}
