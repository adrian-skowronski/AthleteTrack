<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'name', 'surname', 'email', 'password', 'birthdate', 'points', 'phone', 'role_id', 
        'remember_token', 'category_id','sport_id', 'approved', 'photo', 'is_active',

    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdmin()
{
    return $this->role_id === 1;
}

public function isCoach()
{
    return $this->role_id === 2; 
}

public function isAthlete()
{
    return $this->role_id === 3; 
}

    public function category()
{
    return $this->belongsTo(Category::class, 'category_id', 'category_id');
}

    public function sport()
    {
        return $this->belongsTo(Sport::class, 'sport_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_hill', 'user_id', 'event_id');
    }

    public function trainings()
    {
        return $this->belongsToMany(Training::class, 'training_user', 'user_id', 'training_id');
    }



   public function updateCategoryByPoints(): void
{
    if ($this->points === null) {
        return;
    }

    $newCategory = Category::where('min_points', '<=', $this->points)
        ->orderByDesc('min_points')
        ->first();

    if ($newCategory && $this->category_id !== $newCategory->category_id) {
        $this->category_id = $newCategory->category_id;
        $this->save();
    }
}

}