<x-filament-panels::page>

    @vite([
        'resources/css/filament/tasks/filamentTasks.css',
        'resources/css/filament/buttons.css'
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
        <div class="task-element">
            <div class="task-title-container">
                <div class="task-title">
                    <div class="task-status">
                        <div class="info circle animated">
                            <span class="info"></span>
                        </div>
                    </div>
                    <h2>Tarea maquetado</h2>
                </div>
                <div class="task-actions">
                    <div style="margin-right: 10px" wire:loading wire:target="mountAction('edit')">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path class="opacity-75" fill="gray" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <div style="margin-right: 10px" wire:loading wire:target="mountAction('delete')">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path class="opacity-75" fill="gray" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <x-gmdi-edit-note-r class="info-icon"/>
                    <x-mdi-delete-empty class="danger-icon"/>
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
                        Carlos Daniel Medina Sahagún
                    </p>
                </div>
            </div>
            <div class="task-footer" x-data="{ active: false}">
                <div class="task-description">
                    <div class="task-description-content" x-bind:class="active ? 'active' : ''">
                        <p>
                            Lorem Ipsum is simply dummy text of the printing and typesetting
                            industry. Lorem Ipsum has been the industry's standard dummy text
                            ever since the 1500s, when an unknown printer took a galley of type
                            and scrambled it to make a type specimen book. It has survived not only
                            five centuries, but also the leap into electronic typesetting,
                            remaining essentially unchanged. It was popularised in the 1960s with the
                            release of Letraset sheets containing Lorem Ipsum passages, and more recently
                            with desktop publishing software like Aldus PageMaker including versions
                            of Lorem Ipsum.
                            Where does it come from?
                            Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots
                            in a piece of classical Latin literature from 45 BC, making it over 2000
                            years old. Richard McClintock, a Latin professor at Hampden-Sydney College in
                            Virginia, looked up one of the more obscure Latin words, consectetur, from
                            a Lorem Ipsum passage, and going through the cites of the word in classical literature,
                            discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of
                            "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC.
                            This book is a treatise on the theory of ethics, very popular during the Renaissance.
                            The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section
                            1.10.32.

                            The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those
                            interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero
                            are also reproduced in their exact original form, accompanied by English versions
                            from the 1914 translation by H. Rackham.
                        </p>
                        <div class="task-files">
                            <h3>Archivos adjuntos</h3>
                            <div class="task-file">
                                <x-fas-file class="task-file-icon"/>
                                <p>nombre de mi archivo</p>
                            </div>

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
    </div>

</x-filament-panels::page>
