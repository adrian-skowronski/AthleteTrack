<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Sport extends Model
{
    use HasFactory;

    protected $primaryKey = 'sport_id';

    protected $fillable = ['name', 'is_active'];

    public function users()
    {
        return $this->hasMany(User::class, 'sport_id');
    } 
}