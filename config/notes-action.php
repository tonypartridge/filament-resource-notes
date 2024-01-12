<?php

// config for Tonypartridge/FilamentNotesAction
return [
    'note_model' => \Tonypartridge\FilamentNotesAction\Models\Note::class,
    'user_model' => \App\Models\User::class, // Might be: \App\User::class if legacy
    'team_model' => null, //'\App\Models\Team', Based on spatie teams, ensure model has a current_team_id attribute on user model.
    'pinning' => true,
    'has_actionable_notes' => true, // Allows setting a data for actioning
    'sub_comments' => true,
    'has_media' => true, // Requires Spatie Media Plugin
    'media_collection' => 'notes',
    'media_storage_path' => 'app/public/',
];
