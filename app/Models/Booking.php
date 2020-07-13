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
        // TODO: Перенести в сервис?
        // TODO: Добавить индексы?

        foreach ($value as $item) {
            $relations = explode('.', $item['field']);
            $item['column'] = array_pop($relations);
            $item['relation'] = array_shift($relations);

            $baseModel = $this->getModel();
            $item['baseTable'] = $baseModel->getTable();

            if (!empty($item['relation'])) {
                $relatedRelation = $baseModel->{$item['relation']}();
                $relatedModel = $relatedRelation->getRelated();
                $morphMap = $relatedRelation->morphMap();

                $item['relatedTable'] = $relatedModel->getTable();
                $item['relatedKey'] = $relatedRelation->getQualifiedForeignKeyName();
                $item['ownerKey'] = $relatedRelation->getQualifiedOwnerKeyName();
                if (!empty($morphMap)) {
                    $item['morphType'] = $relatedRelation->getMorphType();
                }
            }

            $builder->select($item['baseTable'] . '.*');

            if (empty($item['relation'])) {
                // order without relation
                $builder->orderBy($item['baseTable'] . '.' . $item['column'], $item['order']);

            } elseif (empty($morphMap)) {
                // order with relation
                $builder
                    ->leftJoin($item['relatedTable'], $item['relatedKey'], '=', $item['ownerKey'])
                    ->orderBy($item['relatedTable'] . '.' . $item['column'], $item['order']);

            } else {
                // order with morph relation
                foreach ($morphMap as $morphTable => $morphModel) {
                    $morphKey = (new $morphModel)->getKeyName();
                    $builder->leftJoin($morphTable, function ($join) use ($item, $morphTable, $morphKey) {
                        $join
                            ->on($item['relatedKey'], '=', $morphTable . '.' . $morphKey)
                            ->where($item['baseTable'] . '.' . $item['morphType'], '=', $morphTable);
                    });
                }

                $tables = array_keys($morphMap);
                $columns = explode('|', $item['column']);
                for ($i = 0; $i < sizeof($columns); $i++) {
                    $columns[$i] = $tables[$i] . '.' . $columns[$i];
                }

                $builder->orderByRaw('concat_ws("",' . implode(',', $columns). ') ' . $item['order']);
            }
        }

        Log::info($builder->toSql());

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
