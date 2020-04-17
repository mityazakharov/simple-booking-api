<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Booking extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'begin_at',
        'end_at',
        'status_id',
        'client_id',
        'agent_type',
        'agent_id',
        'place_id',
        'info',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'begin_at' => 'datetime',
        'end_at'   => 'datetime',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'begin_at',
        'end_at',
    ];

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function agent(): MorphTo
    {
        return $this->morphTo();
    }

    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }
}