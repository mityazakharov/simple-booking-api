<?php

namespace App\Models;

use Fico7489\Laravel\EloquentJoin\Traits\EloquentJoin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Log;

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

//    protected static function boot(){
//        parent::boot();
//
//        Relation::morphMap([
//            'App\Models\Employer',
//            'App\Models\Renter',
//        ]);
//    }


    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::saving(function ($model) {
            // TODO: Check time slot here or in Observer
        });
    }


    /**
     * Add a order by constrained upon the query.
     *
     * @param Builder $builder
     * @param mixed $value
     * @return Builder
     */
    public function orderBy($builder, $value)
    {
        Log::info($value);
//        $builder->orderByJoin($value[0]['field'], $value[0]['order']);
        foreach ($value as $item) {
            $relations = explode('.', $item['field']);
            $column = array_pop($relations);
            $relation = array_shift($relations);
            $baseModel = $this->getModel();
            $baseTable = $baseModel->getTable();
            $basePrimaryKey = $baseModel->getKeyName();

            if ($relation) {
                $relatedRelation = $baseModel->$relation();
                $relatedModel = $relatedRelation->getRelated();
                $relatedPrimaryKey = $relatedModel->getKeyName();
                $relatedTable = $relatedModel->getTable();
                $morphMap = $relatedRelation->morphMap();

                $relatedKey = $relatedRelation->getQualifiedForeignKeyName();
//                $relatedKey = last(explode('.', $relatedKey));
                $ownerKey = $relatedRelation->getQualifiedOwnerKeyName(); //getOwnerKeyName();
                $parentKey = $relatedRelation->getQualifiedParentKeyName();
            }
            Log::info(print_r([
                'relation' => $relation,
                'column' => $column,
                'baseModel' => get_class($baseModel),
                'baseTable' => $baseTable,
                'basePrimaryKey' => $basePrimaryKey,
//                'relatedRelation' => $relatedRelation,
                'relatedModel' => get_class($relatedModel),
                'relatedPrimaryKey' => $relatedPrimaryKey,
                'relatedTable' => $relatedTable,
                'relatedKey' => $relatedKey,
                'ownerKey' => $ownerKey,
                'parentKey' => $parentKey,
                'morphMap' => $morphMap,
            ],true));

            $builder->select($baseTable . '.*');

            if (empty($relation)) {
                $builder->orderBy($baseTable . '.' . $column, $item['order']);
            } elseif (empty($morphMap)) {
                $builder->leftJoin($relatedTable, $relatedKey, '=', $ownerKey);
                $builder->orderBy($relatedTable . '.' . $column, $item['order']);
            } else {
                Log::info(print_r($relatedRelation->getMorphType(),true));
                foreach ($morphMap as $morphTable => $morphModel) {
                    $builder->leftJoin($morphTable, function ($join) use ($relatedRelation, $morphTable, $morphModel) {
                        $join
                            ->on($relatedRelation->getQualifiedForeignKeyName(), '=', $morphTable . '.' . (new $morphModel)->getKeyName())
                            ->where(/*$baseTable . '.' . */ $relatedRelation->getMorphType(), '=', $morphTable);
                    });
                }
                $builder->orderByRaw('concat_ws("", renters.title, employers.last_name) ' . $item['order']);
            }
//    if(
//        b.agent_type = 'renters',
//        r.title,
//        concat_ws(' ', e.last_name, e.first_name)
//    ) as title,
//                concat_ws('', r.title, e.last_name) as title2
//from
//    bookings b
//    left join employers e on b.agent_id = e.id and agent_type='employers'
//    left join renters r on b.agent_id = r.id and agent_type='renters'
//order by title;
//            }

        }

        Log::info($builder->toSql());
        Log::info($builder->getBindings());

        return $builder;
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
