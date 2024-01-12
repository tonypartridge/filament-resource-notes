<?php

namespace Tonypartridge\FilamentNotesAction\Actions;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;

class NotesAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'notesaction';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('Notes'))
            ->form(self::noteFormSchema())
            ->action(function (array $data, $record, $form) {
                $note = $record->filament_notes()->create([
                    'parent_id' => null,
                    'note_body' => Arr::get($data, 'note_body', ''),
                    'user_id' => auth()->user()->id,
                    'team_id' => auth()->user()->current_team_id,
                    'action_date' => Arr::get($data, 'action_date', null),
                    'is_pinned' => Arr::get($data, 'is_pinned', false),
                ]);
                if (isset($data['media'])) {
                    foreach ($data['media'] as $file) {
                        $fileAdder = $note->addMedia(storage_path(config(
                            'notes-action.storage_path',
                            'app/public/'
                        ) . $file))
                            ->toMediaCollection(
                                config('notes-action.media_collection', 'notes')
                            );

                        $fileAdder->file_name = md5($fileAdder->uuid) . '.' . $fileAdder->extension;
                        $fileAdder->save();
                    }
                }
                Notifications\Notification::make()
                    ->title(__('Note added!'))
                    ->success()
                    ->send();
                $form->fill();
                $this->halt();
            })
            ->icon('heroicon-m-pencil-square')
            ->color('gray')
            ->modalCancelAction(false)
            ->modalSubmitActionLabel(__('Add Note'))
            ->slideOver()
            ->badge(badge: fn () => $this->getRecord()->filament_notes->count() > 0 ? $this->getRecord()->filament_notes->count() : null)
            ->badgeColor('gray')
            ->registerModalActions([
                Actions\Action::make('delete')
                    ->iconButton()
                    ->color('gray')
                    ->icon('heroicon-m-trash')
                    ->size(\Filament\Support\Enums\ActionSize::ExtraSmall)
                    ->requiresConfirmation()
                    ->action(function (array $arguments) {
                        \Tonypartridge\FilamentNotesAction\Models\Note::findOrFail($arguments['note'])->delete();
                        Notifications\Notification::make()
                            ->title(__('Note deleted!'))
                            ->success()
                            ->send();
                    }),
                Actions\Action::make('pinNote')
                    ->visible(fn () => config('notes-action.pinning', true))
                    ->iconButton()
                    ->color(function (array $arguments) {
                        return $arguments['pinned'] ? 'primary' : 'gray';
                    })
                    ->icon('heroicon-m-star')
                    ->size(\Filament\Support\Enums\ActionSize::ExtraSmall)
                    ->action(function (array $arguments) {
                        $note = \Tonypartridge\FilamentNotesAction\Models\Note::findOrFail($arguments['note']);
                        $note->is_pinned = ! $note->is_pinned;
                        $note->save();
                    }),
                Actions\Action::make('sub_note')
                    ->visible(fn () => config('notes-action.sub_comments', true))
                    ->label(__('Add Sub Comment'))
                    ->color('primary')
                    ->icon('heroicon-m-chat-bubble-left-right')
                    ->size(\Filament\Support\Enums\ActionSize::ExtraSmall)
                    ->form([
                        Forms\Components\RichEditor::make('note_body')
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'blockquote',
                                'bold',
                                'h2',
                                'h3',
                                'italic',
                                'link',
                                'orderedList',
                                'bulletList',
                                'redo',
                                'strike',
                                'underline',
                                'undo',
                            ])
                            ->required()
                            ->label('Comment:'),
                    ])
                    ->slideover()
                    ->action(function (array $arguments, $data, $record, $form) {
                        $note = $record->filament_notes()->create([
                            'parent_id' => Arr::get($arguments, 'parent_id'),
                            'note_body' => Arr::get($data, 'note_body'),
                            'user_id' => auth()->user()->id,
                            'team_id' => auth()->user()->current_team_id,
                            'action_date' => null,
                            'is_pinned' => false,
                        ]);

                        Notifications\Notification::make()
                            ->title(__('Sub Note added!'))
                            ->success()
                            ->send();
                        $form->fill();
                    }),
                Actions\Action::make('download-file')
                    ->visible(fn () => config('notes-action.has_media', true))
                    ->iconButton()
                    ->color('primary')
                    ->icon('heroicon-m-document-arrow-down')
                    ->size(\Filament\Support\Enums\ActionSize::ExtraSmall)
                    ->action(function (array $arguments) {
                        return response()->download($arguments['media-file']);
                    }),
                Actions\Action::make('preview')
                    ->visible(fn () => config('notes-action.has_media', true))
                    ->label(__('Preview'))
                    ->iconButton()
                    ->color('primary')
                    ->icon('heroicon-o-eye')
                    ->modalContentFooter(
                        function (array $arguments) {
                            return view(
                                'filament.actions.notes.media-browser',
                                [
                                    'mediaUrl' => $arguments['media-url'],
                                    'mediaMimeType' => $arguments['media-mime-type'],
                                ],
                            );
                        }
                    )
                    ->modalSubmitAction(false),
            ])
            ->modalContent(fn ($record, Actions\Action $action): View => view(
                'filament.actions.notes.show-notes',
                ['notes' => $record->filament_notes()->orderByRaw('is_pinned DESC, created_at DESC')->get()],
                ['action' => $action],
            ));
    }

    public function noteFormSchema(): array
    {
        return [
            Forms\Components\Section::make(__('Add note'))
                ->icon('heroicon-m-pencil-square')
                ->compact()
                ->columns(2)
                ->schema(components: [
                    Forms\Components\RichEditor::make('note_body')
                        ->columnSpanFull()
                        ->toolbarButtons([
                            'blockquote',
                            'bold',
                            'h2',
                            'h3',
                            'italic',
                            'link',
                            'orderedList',
                            'bulletList',
                            'redo',
                            'strike',
                            'underline',
                            'undo',
                        ])
                        ->required()
                        ->label('Note:'),
                    Forms\Components\FileUpload::make('media')
                        ->visibility('private')
                        ->preserveFilenames()
                        ->dehydrated(true)
                        ->multiple()
                        ->previewable(false)
                        ->columnSpanFull()
                        ->visible(fn () => config('notes-action.has_media', true))
                        ->label(__('Note Media/File(s)')),
                    Forms\Components\Toggle::make('action_required')
                        ->visible(fn () => config('notes-action.has_actionable_notes', true))
                        ->label(__('Requires Action?'))
                        ->inline(false)
                        ->live()
                        ->default(false),
                    Forms\Components\DatePicker::make('action_date')
                        ->visible(fn () => config('notes-action.has_actionable_notes', true))
                        ->label(__('Action By Date'))
                        ->required(true)
                        ->visible(fn (Forms\Get $get) => $get('action_required'))
                        ->default(null),
                ]),
        ];
    }
}
