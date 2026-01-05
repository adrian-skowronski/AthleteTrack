<?php

use App\Http\Controllers\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/events', function () {
    return App\Models\Event::all()->map(function ($event) {
        return [
            'id' => $event->event_id,
            'title' => $event->name,
            'start' => $event->date . 'T' . $event->start_hour,
            'description' => $event->description,
            'date' => $event->date,
            'start_hour' => $event->start_hour,
        ];
    });
});