<?php

namespace App\Filament\Pages;

use App\Models\Group;
use App\Models\GroupUser;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\CheckboxList;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;


class Groups extends Page
{
    protected static ?string $navigationIcon = 'heroicon-s-rectangle-stack';

    protected static string $view = 'filament.pages.groups';

    protected static ?string $title = 'Mis grupos';

    protected static ?string $navigationGroup = 'Grupos';

    // Obtained the registered users by the logged user
    protected function getRegisteredUsers() {
        return User::where('registered_by', Auth::id())->get();
    }

    // Obtained the registered users by the logged user and those who aren't inside the selected group
    protected function getRegisteredUsersNotAssigned($group_id){
        return User::where('registered_by', Auth::id())->whereDoesntHave('groups', function($query) use ($group_id){
            $query->where('group_id', $group_id);
        })->select('id','name')->get();
    }

    // Get the users registered inside the selected group
    protected function getGroupUsers($group_id){
        return Group::find($group_id)->users;
    }

    //Get the groups created by the logged users
    protected function getGroups(){
        if(Auth::user()->role->role == 'Administrador'){
            return Group::where('user_id',Auth::id())->withCount(['users','tasks'])->get();
        }

        if(Auth::user()->role->role == 'Colaborador'){
            return Group::whereHas('users', function($query){
                $query->where('user_id', Auth::id());
            })->withCount(['users','tasks'])->get();
        }
    }

    protected function getHeaderActions(): array {
        return [
            //Action to create a new group
            CreateAction::make()->label('Crear grupo')->steps([
                Step::make('group')->label('Crear grupo')->schema([
                    TextInput::make('name')->label('Nombre')->required()->maxLength(255),
                    RichEditor::make('description')->label('Descripción')->required()->maxLength(300)->columnSpan(2)
                ])->columns(2),
                Step::make('group_user')->label('Registrar usuarios')->schema([
                    CheckboxList::make('users')->label('Usuarios disponibles:')->options(
                        $this->getRegisteredUsers()->pluck('name','id')
                    )->label('Usuarios a añadir')
                ])->columns(2),
                ])->modalHeading('Formulario creación de grupo')->action(function(array $data){

                // Create group
                $group = Group::create([
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'user_id' => Auth::id()
                ]);

                $groupUsers = [];

                // Get the user to save inside the group
                foreach ($data['users'] as $user) {
                    $groupUsers[] = [
                        'user_id' => $user,
                        'group_id' => $group->id
                    ];
                }

                //Register users inside the group
                GroupUser::insert($groupUsers);

                // Send success notification to the page
                Notification::make()->title('Grupo creado')->success()->send();
            })->modalCancelActionLabel('Cancelar')->modalButton('Guardar')
            ->authorize('create',Group::class)
        ];
    }

    //Action to delete the selected group
    protected function deleteAction(){
        return Action::make('delete')->requiresConfirmation()->modalHeading('Eliminar grupo')
            ->modalDescription('¿Estás seguro/a de eliminar este grupo?')->modalCancelActionLabel('Cancelar')
            ->modalSubmitActionLabel('Eliminar')->color('danger')
            ->action(function(array $arguments){
                Group::destroy($arguments['id']);
                Notification::make()->title('Grupo eliminado exitosamente')->success()->send();
            });
    }

    //Action to edit the selected group
    protected function editAction(): Action{
        return EditAction::make('edit')
            ->record(function (array $arguments): Group {
                return Group::find($arguments['id']);
            })
            ->modalHeading('Editar grupo')
            ->modalDescription('Formulario edición de grupo')
            ->color('info')
            ->modalButton('Modificar')
            ->modalCancelActionLabel('Cancelar')
            ->form([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(150),
                RichEditor::make('description')
                    ->label('Descripción')
                    ->required()
                    ->maxLength(300)
                    ->columnSpan(2)
            ]);
    }

    //Action to administrate the users inside the selected group
    protected function adminUsers(){
        return Action::make('adminUsers')->modalHeading('Gestión de usuarios')
            ->modalDescription('Añade o remueve usuarios del grupo')->color('warning')->modalButton('Guardar')
            ->modalCancelActionLabel('Cancelar')
            ->form(function (array $arguments): array {
                $group = Group::find($arguments['id']);
                return [
                    CheckboxList::make('oldUsers')->label('Usuarios a remover')
                        ->options(
                            $group->users->pluck('name','id')
                        ),
                    CheckboxList::make('newUsers')->label('Usuarios a añadir')
                        ->options(
                            $this->getRegisteredUsersNotAssigned($group->id)->pluck('name','id')
                        )->label('Usuarios a añadir')
                    ];
            })->action(function (array $data, array $arguments){
                $group = Group::find($arguments['id']);
                $usersToInsert = [];

                foreach($data['newUsers'] as $user){
                    $usersToInsert[] = [
                        'user_id' => $user,
                        'group_id' => $group->id
                    ];
                }

                GroupUser::insert($usersToInsert);
                GroupUser::where('group_id',$group->id)->whereIn('user_id',$data['oldUsers'])->delete();
                Notification::make()->title('Gestión de usuarios realizada exitosamente')->success()->send();
            });
    }
}



