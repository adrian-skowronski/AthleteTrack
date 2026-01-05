<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Event extends Model
{
    use HasFactory;

    protected $primaryKey = 'event_id';

    protected $fillable = [
        'required_category_id', 'age_from', 'age_to', 'name', 'description', 'date', 'start_hour', 'max_participants'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'event_user', 'event_id', 'user_id');
    }

    public function requiredCategory()
    {
        return $this->belongsTo(Category::class, 'required_category_id');
    }

    
}