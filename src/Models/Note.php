<?php

namespace Tonypartridge\FilamentNotesAction\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Note extends Model implements \App\Filament\Contracts\NotesContract, HasMedia
{
    use \App\Filament\Concerns\InteractsWithNotes;
    use HasFactory;
    use InteractsWithMedia;
    use SoftDeletes;

    public $timestamps = true;

    protected $fillable = [
        'parent_id',
        'action_date',
        'is_pinned',
        'team_id',
        'user_id',
        'notable_type',
        'notable_id',
        'note_body',
    ];

    protected $casts = [
        'action_date' => 'datetime',
        'is_pinned' => 'boolean',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            config('notes-action.user_model')
        );
    }

    public function sub_notes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            config('notes-action.note_model'),
            'parent_id'
        );
    }
}
