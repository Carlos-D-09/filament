<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageUsers extends ManageRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Crear usuario')->
            modalHeading('Formulario creación de usuario')->modalButton('Guardar')
            ->createAnother(false)->modalCancelActionLabel('Cancelar')
        ];
    }
}
