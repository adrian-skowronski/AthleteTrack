<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

class Training extends Model
{
    use HasFactory;
    protected $primaryKey = 'training_id';

    protected $fillable = [
        'description', 'date', 'start_time', 'end_time', 'trainer_id', 'max_points'
    ];

    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id', 'user_id');
    } //trainer_id relacja 1 do 1 z user_id z tabeli users
 
    public function users()
    {
        return $this->belongsToMany(User::class, 'training_user', 'training_id', 'user_id');
    }


    public function userCanSignUp($user)
    {
        // czy użytkownik jest sportowcem i uprawia ten sam sport, co trener prowadzący trening
        if ($user->role_id != 3 || $user->sport_id != $this->trainer->sport->sport_id) {
            return false;
        }

        // czy użytkownik jest już zapisany na ten trening
        if ($user->trainings()->where('training_user.training_id', $this->id)->exists()) {
            return false;
        }

        // czy użytkownik jest zapisany na inny trening w tym samym dniu
        $sameDayTrainings = $user->trainings()->whereDate('trainings.date', $this->date)->count();
        if ($sameDayTrainings > 0) {
            return false;
        }

        $trainingWeek = Carbon::parse($this->date)->startOfWeek();

        // czy użytkownik jest zapisany na więcej niż 3 treningi w tygodniu treningu
        $weekTrainings = $user->trainings()
            ->whereBetween('trainings.date', [$trainingWeek, $trainingWeek->copy()->endOfWeek()])
            ->count();
        if ($weekTrainings >= 3) {
            return false;
        }

        // czy dzień treningu już nadszedł
        $now = Carbon::now();
        $trainingStart = Carbon::parse($this->date)->startOfDay();
        if(!$now->lt($trainingStart))
        {
            return false;
        }
        


        return true;
    }
    public function isUserSignedUp(User $user)
{
    return $this->users->contains($user);
}
}