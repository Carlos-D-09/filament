<?php

namespace App\Filament\Pages;

use App\Models\File;
use App\Models\Group;
use App\Models\Task;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

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

    public ?int $group_id = null;

    public function mount($group_id){
        $this->group_id = $group_id;
        $group = Group::find($group_id);
        static::$title = 'Tareas en el grupo ' . $group->name;
    }

    //Get the tasks inside the selected group
    protected function getTasks(){
        return Task::where('group_id', $this->group_id)->get();
    }

    //Get the users registeres by the logged user
    protected function getRegisteredUsers(){
        return User::where('registered_by', Auth::id())
            ->whereHas('groups', function($query){
                $query->where('group_id', $this->group_id);
            })
            ->select('id','name')
            ->get();
    }

    //Check if the logged user can see the edit button on a task
    public function evaluateTaskEdition($task){
        if(Auth::user()->role->role == 'Administrador'){
            return $task->group->user->id == Auth::user()->id;
        }

        if(Auth::user()->role->role == 'Colaborador'){
            if (
                $task->completed == false &&
                (
                    $task->assigned_to == Auth::id() ||
                    ($task->assigned_to == null && $task->created_by == Auth::id())
                )
            ){
                return true;
            }
        }

        return false;
    }


    //Check if the logged user can see the delete button on a task
    public function evaluateTaskDelete($task){
        if(Auth::user()->role->role == 'Administrador' && $task->group->user_id == Auth::user()->id){
            return true;
        }

        if (
            Auth::user()->role->role == 'Colaborador' &&
            $task->completed == false &&
            $task->created_by == Auth::user()->id &&
            (
                $task->assigned_to == Auth::id() ||
                $task->assigned_to == null
            )
        ){
            return true;

        }
        return false;
    }

    protected function getHeaderActions(): array{
        return [
            //Action to create a new group
            CreateAction::make()->label('Crear tarea')->form([
                Grid::make()->schema([
                    TextInput::make('name')
                        ->label('Nombre')
                        ->required()
                        ->maxLength(150),
                    Select::make('assigned_to')
                        ->options( function(): array{
                            if(Auth::user()->role->role == 'Colaborador'){
                                return [
                                    Auth::id() => Auth::user()->name .' (Yo)'
                                ];
                            }
                            if(Auth::user()->role->role == 'Administrador'){
                                $users = [
                                    Auth::id() => Auth::user()->name .' (Yo)'
                                ];

                                foreach($this->getRegisteredUsers() as $user){
                                    $users[$user->id] = $user->name;
                                }

                                return $users;
                            }
                        })
                        ->placeholder('Sin asignación'),
                    Grid::make()
                        ->schema([
                            DatePicker::make('start_at'),
                            DatePicker::make('end_at'),
                        ])
                        ->columns(2)
                        ->columnSpan(2),
                    RichEditor::make('description')
                        ->label('Descripción')
                        ->required()
                        ->maxLength(500)
                        ->columnSpan(2),
                    FileUpload::make('files')
                        ->label('Archivos adjuntos')
                        ->multiple()
                        ->directory('users/docs')
                        ->storeFileNamesIn('originalNames')
                    ])->columns(2)
            ])->action(function (array $data){
                $task = Task::create([
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'start_at' => is_null($data['start_at']) ? null : $data['start_at'],
                    'end_at' => is_null($data['end_at']) ? null : $data['end_at'],
                    'group_id' => $this->group_id,
                    'created_by' => Auth::id(),
                    'assigned_to' => $data['assigned_to'] == 0 ? null : $data['assigned_to']
                ]);

                $files = [];
                for ($i=0; $i<count($data['files']); $i++){
                    $file = $data['files'][$i];
                    $name = $data['originalNames'][$file];
                    $files[] = [
                        'name' => $name,
                        'route' => $file,
                        'created_at' => now(),
                        'updated_at' => now(),
                        'task_id' => $task->id
                    ];
                }
                File::insert($files);
            })
            ->createAnother(false)
            ->authorize('create',Task::class)
        ];
    }

    protected function deleteAction(){
        return Action::make('delete')
            ->record(function(array $arguments) {
                return Task::find($arguments['id']);
            })
            ->requiresConfirmation()
            ->modalHeading('Eliminar tarea')
            ->modalDescription('¿Estás seguro/a de eliminar esta tarea?')->modalCancelActionLabel('Cancelar')
            ->modalSubmitActionLabel('Eliminar')->color('danger')
            ->action(function(array $arguments){
                Task::destroy($arguments['id']);
                Notification::make()->title('Tarea eliminada exitosamente')
                ->success()
                ->send();
            })
            ->authorize('delete', Task::class);
    }

}
