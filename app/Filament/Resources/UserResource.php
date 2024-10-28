<?php

namespace App\Filament\Resources;

use App\Models\User;
use App\Filament\Resources\UserResource\Pages;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $label = 'Usuarios';

    protected static ?string $model = User::class;

    public static function canViewAny(): bool
    {
        return Auth::user()->role->role === 'Administrador';
    }

    protected static ?string $navigationIcon = 'heroicon-s-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Nombre')->required(),
                Select::make('role_id')->label('Rol')->relationship(
                    name:'role',
                    titleAttribute: 'role'
                )->hiddenOn('edit')->required(),
                TextInput::make('email')->label('Correo electrónico')->email()->columnSpan(2)->required()->unique(table: User::class, ignoreRecord:true),
                TextInput::make('password')->password()->columnSpan(2)->hiddenOn('edit')->required(),
                FileUpload::make('profile_picture')->label('Foto de perfil')->image()->directory('users/images')->default(User::DEFAULT_USER_IMAGE),
                Hidden::make('registered_by')->default(Auth::id())
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table->modifyQueryUsing(fn (Builder $query) => $query->where('registered_by', Auth::id())->where('role_id',2))->columns([
                TextColumn::make('name')->icon('heroicon-s-user')->label('Nombre'),
                TextColumn::make('email')->icon('heroicon-m-envelope')->label('Correo electrónico'),
                TextColumn::make('created_at')->dateTime('d/m/Y')->icon('heroicon-s-calendar')->label('Fecha de creación'),
                TextColumn::make('role.role')->badge()->color(fn (string $state): string => match ($state) {
                    'Administrador' => 'success',
                    'Colaborador' => 'info',
                    default => 'danger'
                })->label('Rol'),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make()->label('Modificar')->modalHeading('Formulario edición usuario')->modalCancelActionLabel('Cancelar')
                    ->modalButton('Guardar cambios'),
                    DeleteAction::make()->label('Eliminar')->modalHeading('Eliminar usuario')
                    ->modalDescription('¿Estás seguro de que deseas eliminar el usuarios?')->modalCancelActionLabel('Cancelar')
                    ->modalButton('Eliminar'),
                ])
            ])
            ->bulkActions([
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}
