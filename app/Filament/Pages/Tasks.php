<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Tasks extends Page
{
    protected static ?string $navigationIcon = null;
    protected static ?string $navigationLabel = null;
    protected static ?string $navigationGroup = null;
    protected static ?int $navigationSort = null;

    protected static string $view = 'filament.pages.tasks';

    protected static ?string $slug = '/groups/{group_id}/tasks'
}
