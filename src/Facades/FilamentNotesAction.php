<?php

namespace Tonypartridge\FilamentNotesAction\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Tonypartridge\FilamentNotesAction\FilamentNotesAction
 */
class FilamentNotesAction extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Tonypartridge\FilamentNotesAction\FilamentNotesAction::class;
    }
}
