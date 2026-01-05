<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\EventUser;
use App\Models\TrainingUser;
use Illuminate\Http\Request;


class AthletePanelController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $eventUser = EventUser::get();
        $birthdate = Carbon::parse(Auth::user()->birthdate);
        $age = $birthdate->age;

        $trainings = TrainingUser::join('trainings', 'training_user.training_id', '=', 'trainings.training_id')
        ->join('users', 'trainings.trainer_id', '=', 'users.user_id')
        ->where('training_user.user_id', $user->user_id)
        ->select('training_user.*', 'trainings.*', 'users.name as trainer_name', 'users.surname as trainer_surname')
        ->orderBy('trainings.date', 'desc')
        ->paginate(10);

        $allTrainings = TrainingUser::join('trainings', 'training_user.training_id', '=', 'trainings.training_id')
        ->join('users', 'trainings.trainer_id', '=', 'users.user_id')
        ->where('training_user.user_id', $user->user_id)
        ->select('training_user.*', 'trainings.*', 'users.name as trainer_name', 'users.surname as trainer_surname')
        ->orderBy('trainings.date', 'desc')
        ->get();
    
        /////////////

        $events = DB::table('event_user')
            ->join('events', 'event_user.event_id', '=', 'events.event_id')
            ->where('event_user.user_id', $user->user_id)
            ->select('events.*')
            ->orderBy('date', 'desc')
            ->paginate(10);

            /////////////
            if ($request->ajax()) {
    if ($request->type === 'trainings') {
        return view('athlete.partials.trainings_table', compact('trainings'))->render();
    } elseif ($request->type === 'events') {
        return view('athlete.partials.events_table', compact('events'))->render();
    }
}
///

            $total_trainings = 0;
            foreach ($allTrainings as $training) {
                $presence = DB::table('training_user') //spr obecności
                    ->where('training_id', $training->training_id)
                    ->where('user_id', $user->user_id)
                    ->where('status', 'obecność')
                    ->exists();
            
                if ($presence) {
                    $total_trainings++;
                }
            }
            
            //łączny czas na treningach użytkownika
            $total_time = 0;
            foreach ($allTrainings as $training) {

                $presence = DB::table('training_user') //sprawdzenie czy był obecny sportowiec na treningu
                    ->where('training_id', $training->training_id)
                    ->where('user_id', $user->user_id)
                    ->where('status', 'obecność')
                    ->exists();
            
                if ($presence) {
                    $start_time = Carbon::parse($training->start_time);
                    $end_time = Carbon::parse($training->end_time);
                    $total_time += $start_time->diffInHours($end_time);
                }
                
            }
            
            //ostatni trening z obecnością użytkownika

            $last_training = DB::table('training_user')
    ->join('trainings', 'training_user.training_id', '=', 'trainings.training_id')
    ->where('training_user.user_id', $user->user_id)
    ->where('training_user.status', 'obecność')
    ->orderByDesc('trainings.date')
    ->select('trainings.description', 'trainings.date', 'training_user.status', 'training_user.points')
    ->first();

    //ostatni opuszczony trening
     $last_missed_training = DB::table('training_user')
    ->join('trainings', 'training_user.training_id', '=', 'trainings.training_id')
    ->where('training_user.user_id', $user->user_id)
    ->whereIn('training_user.status', ['nieobecność nieusprawiedliwiona', 'nieobecność usprawiedliwiona'])
    ->orderByDesc('trainings.date')
    ->select('trainings.description', 'trainings.date', 'training_user.status', 'training_user.points')
    ->first();
    
////////////

    $start_of_current_month = Carbon::now()->startOfMonth();
    $end_of_current_month = Carbon::now()->endOfMonth();

    $trainings_last_month = DB::table('training_user')
            ->join('trainings', 'training_user.training_id', '=', 'trainings.training_id')
            ->where('training_user.user_id', $user->user_id)
            ->whereBetween('trainings.date', [$start_of_current_month, $end_of_current_month])
            ->select('trainings.*', 'training_user.points')
            ->get();

        $total_points_last_month = $trainings_last_month->sum('points');

        ////////////// PIERWSZY WYKRES - średni czas na treningu w tygodniu
        $trainingData = [];
        for ($i = 0; $i < 8; $i++) {
            $start_of_week = Carbon::now()->subWeeks($i)->startOfWeek();
            $end_of_week = Carbon::now()->subWeeks($i)->endOfWeek();
            $total_time_per_week = DB::table('training_user')
                ->join('trainings', 'training_user.training_id', '=', 'trainings.training_id')
                ->where('training_user.user_id', $user->user_id)
                ->where('training_user.status', 'obecność')
                ->where('trainings.date', '>=', $start_of_week)
        ->where('trainings.date', '<=', $end_of_week)
                ->sum(DB::raw('TIME_TO_SEC(TIMEDIFF(trainings.end_time, trainings.start_time))'));

                
            $total_trainings_per_week = DB::table('training_user')
                ->join('trainings', 'training_user.training_id', '=', 'trainings.training_id')
                ->where('training_user.user_id', $user->user_id)
                ->where('training_user.status', 'obecność')
                ->where('trainings.date', '>=', $start_of_week)
                ->where('trainings.date', '<=', $end_of_week)
                 ->count();

                 $trainingData[] = [
                    'date' => $start_of_week->format('d-m-Y'),
                    'average_time' => $total_trainings_per_week > 0 ? round($total_time_per_week / ($total_trainings_per_week * 60 * 60), 2) : 0
                ];
        }


            //////DRUGI WYKRES - treningi na miesiąc
            $trainingCounts = [];
        $labels = [];
        for ($i = 0; $i < 4; $i++) {
            $start_of_month = Carbon::now()->subMonths($i)->startOfMonth();
            $end_of_month = Carbon::now()->subMonths($i)->endOfMonth();

            $trainingCount = DB::table('training_user')
                ->join('trainings', 'training_user.training_id', '=', 'trainings.training_id')
                ->where('training_user.user_id', $user->user_id)
                ->where('training_user.status', '=', 'obecność')
                ->whereBetween('trainings.date', [$start_of_month, $end_of_month])
                ->count();

            $trainingCounts[] = $trainingCount;
            $labels[] = $start_of_month->format('Y-m');
        }

        // odwracamy kolejność, aby najnowszy miesiąc był na końcu
        $trainingCounts = array_reverse($trainingCounts);
        $labels = array_reverse($labels);

    return view('athlete.index', compact('trainings', 'events', 'total_trainings', 'total_time', 'last_training', 'age', 'total_points_last_month', 'trainingData', 'trainingCounts', 'labels', 'last_missed_training'));
    }
}
