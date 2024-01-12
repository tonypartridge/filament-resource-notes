<?php

namespace Tonypartridge\FilamentNotesAction\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait InteractsWithNotes
{
    public function filament_notes(): MorphMany
    {

        return $this->morphMany(
            config('notes-action.note_model'),
            'notable'
        )->whereNull('parent_id');
    }
}
