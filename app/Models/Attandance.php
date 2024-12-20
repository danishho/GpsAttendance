<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attandance extends Model
{
    /** @use HasFactory<\Database\Factories\AttandanceFactory> */
    use HasFactory;
    protected $table = 'attandances';
    protected $guarded = [];

    // Define the relationship with the device model
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}
