<x-filament-panels::page>

    @vite([
        'resources/css/filament/groups/filamentGroups.css',
        'resources/css/filament/buttons.css'
    ])
    @assets
    <link rel="stylesheet" href="{{ asset('css/filament/groups/filamentGroups.css')}}">
    <link rel="stylesheet" href="{{ asset('css/filament/buttons.css')}}">
    @endassets

    <div class="groups-container">
        @foreach ($this->getGroups() as $group)
            <div class="group-element" wire:key="{{$group->id}}">
                <div class="group-title">
                    <h2>{{$group->name}}</h2>
                </div>
                <div class="group-actions">
                    @if ($group->user_id == Auth::id())
                        <div class="buttons-actions-container">
                            <div style="margin-right: 10px" wire:loading wire:target="mountAction('adminUsers',{id:{{$group->id}}})">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <path class="opacity-75" fill="gray" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                            <div style="margin-right: 10px" wire:loading wire:target="mountAction('edit',{id:{{$group->id}}})">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <path class="opacity-75" fill="gray" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                            <div style="margin-right: 10px" wire:loading wire:target="mountAction('delete',{id:{{$group->id}}})">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <path class="opacity-75" fill="gray" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                            <div style="margin-right: 10px" wire:loading wire:target="mountAction('visualizeTasks',{id:{{$group->id}}})">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <path class="opacity-75" fill="gray" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                            <x-fas-users-cog  class="success-icon" wire:click.prevent="mountAction('adminUsers',{id:{{$group->id}}})"/>
                            <x-gmdi-edit-note-r class="info-icon" wire:click.prevent="mountAction('edit', {id: {{$group->id}} })"/>
                            <x-mdi-delete-empty class="danger-icon" wire:click.prevent="mountAction('delete',{id:{{$group->id}}})"/>
                        </div>
                    @endif
                </div>
                <div class="group-stats">
                    <div class="collapsed-stats">
                        <x-heroicon-s-clipboard-document-list/>
                        <p>:{{$group->tasks_count}}</p>
                    </div>
                    <div class="collapsed-stats">
                        <x-heroicon-s-users/>
                        <p>:{{$group->users_count}}</p>
                    </div>
                    <p class="uncollapsed-stats">Usuarios: {{$group->users_count}}</p>
                    <p class="uncollapsed-stats">Tareas: {{$group->tasks_count}}</p>
                    <a href="groups/{{$group->id}}/tasks">
                        <x-gmdi-remove-red-eye-r class="tasks-icon-action"/>
                    </a>
                </div>
                <div class="group-description">
                    <span></span>
                    <div class="group-description-content">
                        {!!$group->description!!}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
