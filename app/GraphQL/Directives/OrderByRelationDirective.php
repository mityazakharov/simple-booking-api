<?php

namespace App\GraphQL\Directives;

use Illuminate\Support\Facades\Log;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\ArgBuilderDirective;
use Nuwave\Lighthouse\Support\Contracts\DefinedDirective;

class OrderByRelationDirective extends BaseDirective implements ArgBuilderDirective, DefinedDirective
{
    public static function definition(): string
    {
        return /** @lang GraphQL */ <<<'SDL'
"""
Sort a result list by one or more given columns using relations.
"""
directive @orderByRelation on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION
SDL;
    }

    /**
     * Name of the directive.
     *
     * @return string
     */
    public function name(): string
    {
        return 'orderByRelation';
    }

    /**
     * Apply complex "ORDER BY" clause using relations.
     *
     * @param  \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $builder
     * @param  mixed $value
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function handleBuilder($builder, $value)
    {
        Log::info(print_r($value, true));
        foreach ($value as $item) {
            $relations = explode('.', $item['field']);
            $item['column'] = array_pop($relations);
            $item['relation'] = array_shift($relations);

            $baseModel = $builder->getModel();
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
}
