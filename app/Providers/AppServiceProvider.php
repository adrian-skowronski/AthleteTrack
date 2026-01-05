<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\EventUser;
use App\Models\User;
use App\Models\TrainingUser;
use Carbon\Carbon;
use App\Observers\UserObserver;

class AppServiceProvider extends ServiceProvider
{
 
    public function boot()
    {
   
        Gate::define('remove-athlete', function ($user, EventUser $eventUser) {
           
            $eventDate = Carbon::parse($eventUser->event->date);
            $now = Carbon::now();
            return $now->lt($eventDate);
        });

        Gate::define('assign-points', function ($user, EventUser $eventUser) {
            $eventDate = Carbon::parse($eventUser->event->date);
            $now = Carbon::now();

            return $now->gte($eventDate);
        
        });

        Gate::define('athlete.removeTraining', function ($user, TrainingUser $trainingUser) {

            $trainingDate = Carbon::parse($trainingUser->training->date);
            $now = Carbon::now();
            return $now->lt($trainingDate);
    });
        User::observe(UserObserver::class);

         
    }
}
