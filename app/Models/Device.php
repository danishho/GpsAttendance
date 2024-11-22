<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends Model
{
    use HasFactory;
    protected $guarded =[];

    public function attendances(): HasMany
    {
        return $this->hasMany(Attandance::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
