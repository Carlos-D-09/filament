<x-filament-panels::page>

    @vite([
        'resources/css/filament/tasks/filamentTasks.css',
        'resources/css/buttons.css'
    ])

    <div class="tasks-container">
        <div class="symbology">
            <div class="symbology-element">
                <div class="success circle">
                    <span class="success"></span>
                </div>
                Completed
            </div>
            <div class="symbology-element">
                <div class="info circle">
                    <span class="info"></span>
                </div>
                In progress
            </div>
            <div class="symbology-element">
                <div class="danger circle">
                    <span class="danger"></span>
                </div>
                Overdue
            </div>
        </div>
        @foreach ($this->getTasks() as $task)
            <div class="task-element">
                <div class="task-title-container">
                    <div class="task-title">
                        <div class="task-status">
                            <div class="info circle animated">
                                <span class="info"></span>
                            </div>
                        </div>
                        <h2>{{$task->name}}</h2>
                    </div>
                    <div class="task-actions">
                        @if($this->evaluateTaskEdition($task))
                            <div style="margin-right: 10px" wire:loading wire:target="mountAction('edit')">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <path class="opacity-75" fill="gray" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        @endif
                        @if($this->evaluateTaskDelete($task))
                            <div style="margin-right: 10px" wire:loading wire:target="mountAction('delete', {id:{{$task->id}}})">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <path class="opacity-75" fill="gray" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        @endif
                        @if($this->evaluateTaskEdition($task))
                            <x-gmdi-edit-note-r class="info-icon"/>
                        @endif
                        @if($this->evaluateTaskDelete($task))
                            <x-mdi-delete-empty class="danger-icon" wire:click.prevent="mountAction('delete',{id:{{$task->id}}})"/>
                        @endif

                    </div>
                </div>
                <div class="task-dates">
                    <div class="task-date">
                        <x-fas-calendar-check class="task-date-icon"/>
                        <p>27/10/2024</p>
                    </div>
                    <div class="task-date">
                        <x-fas-calendar-xmark class="task-date-icon"/>
                        <p>29/10/2024</p>
                    </div>
                </div>
                <div class="task-user">
                    <div class="user-tag">
                        <p>
                            {{$task->assigned_to ? $task->assignedTo->name : 'No asignado'}}
                        </p>
                    </div>
                </div>
                <div class="task-footer" x-data="{ active: false}">
                    <div class="task-description">
                        <div class="task-description-content" x-show="active" x-collapse.min.0px.duration.2000ms>
                            {!!$task->description!!}
                            <div class="task-files">
                                <h3>Archivos adjuntos</h3>
                                @foreach ($task->files as $file)
                                    <div class="task-file">
                                        <x-fas-file class="task-file-icon"/>
                                        <a href="{{Storage::url($file->route)}}" target="_blank">{{$file->name}}</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="task-expand-button" x-on:click="active = !active">
                        <div class="arrow" x-bind:class="active ? 'active' : ''">
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</x-filament-panels::page>
