<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'is_loyal',
        'info',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_loyal' => 'boolean',
    ];

    public function employers(): BelongsToMany
    {
        return $this->belongsToMany(Employer::class)->withTimestamps();
    }
}
