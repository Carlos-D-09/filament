<?php

namespace App\Filament\Pages;

use App\Models\Group;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;

class Tasks extends Page
{
    protected static ?string $navigationIcon = null;
    protected static ?string $navigationLabel = null;
    protected static ?string $navigationGroup = null;
    protected static ?int $navigationSort = null;
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $title = null;

    protected static string $view = 'filament.pages.tasks';

    protected static ?string $slug = 'groups/{group_id}/tasks';

    protected ?Group $group = null;

    public function mount($group_id){
        $this->group = Group::find($group_id);
        static::$title = 'Tareas en el grupo ' . $this->group->name;
    }

    protected function getHeaderActions(): array{
        return [
            //Action to create a new group
            CreateAction::make()->label('Crear tarea')->form([
                Grid::make()->schema([
                    TextInput::make('name')->label('Nombre')->required()->maxLength(150),
                    Grid::make()->schema([
                        DatePicker::make('start_at'),
                        DatePicker::make('end_at'),
                    ])->columns(2)->columnSpan(2),
                    RichEditor::make('description')->label('Descripción')->required()->maxLength(500)->columnSpan(2),
                    FileUpload::make('files')->label('Archivos adjuntos')->multiple()
                ])->columns(2)
            ])
        ];
    }

}
