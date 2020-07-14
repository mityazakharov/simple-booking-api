<?php

namespace App\Models;

use Fico7489\Laravel\EloquentJoin\Traits\EloquentJoin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Relation;

class Booking extends Model
{
    use EloquentJoin;

    /**
     * Should use inner join or left join (default = true)
     *
     * @var bool
     */
    protected $leftJoin = true;

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
        'begin_at'  => 'datetime',
        'end_at'    => 'datetime',
        'status_id' => 'integer',
        'client_id' => 'integer',
        'agent_id'  => 'integer',
        'place_id'  => 'integer',
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

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::saving(function ($model) {
            // TODO: Check time slot here or in Observer & remove UniqueValidationDirective (?)
        });
    }

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
        Relation::morphMap([
            'App\Models\Employer',
            'App\Models\Renter',
        ]);

        return $this->morphTo();
    }

    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }
}
