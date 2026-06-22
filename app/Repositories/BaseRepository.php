<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

abstract class BaseRepository
{
    abstract protected function getQuery(): Builder;

    protected function applyDefaultScope(Builder $query): void
    {
    }

    /**
     * Paginated list.
     */
    public function getAll(array $params = []): LengthAwarePaginator
    {
        $query = $this->getQuery();
        $this->applyDefaultScope($query);

        $params = array_merge(request()->all(), $params);
        $query  = $this->applyFiltersSortSearch($query, $params);

        $limit = (int) ($params['limit'] ?? 10);
        $page  = isset($params['page']) ? (int) $params['page'] : null;

        $paginator = $query->paginate($limit, ['*'], 'page', $page);

        $paginator->appends($params);

        return $paginator;
    }

    /**
     * Non-paginated list (optionally limited).
     */
    public function getList(array $params = [])
    {
        $query = $this->getQuery();
        $this->applyDefaultScope($query);

        $query = $this->applyFiltersSortSearch($query, $params);

        if (!empty($params['limit'])) {
            $query->limit((int) $params['limit']);
        }

        return $query->get();
    }

    /**
     * Single record.
     */
    public function getOne(array $params = [])
    {
        $query = $this->getQuery();
        $this->applyDefaultScope($query);

        return $this->applyFiltersSortSearch($query, $params)->first();
    }

    /**
     * Shared filter / search / sort logic used by getAll(), getList(), getOne().
     */
    protected function applyFiltersSortSearch(Builder $query, array $params): Builder
    {
        $sortBy  = $params['sort_by']  ?? $params['order_column'] ?? 'created_at';
        $sortDir = strtolower($params['sort_dir'] ?? $params['order_by'] ?? 'asc');
        $sortDir = in_array($sortDir, ['asc', 'desc'], true) ? $sortDir : 'asc';

        $filterBy = $params['filter_by'] ?? [];
        $search   = $params['search']    ?? null;
        $columns  = $params['columns']   ?? [];

        $with      = (array) ($params['with'] ?? []);
        $withCount = (array) ($params['with_count'] ?? []);

        if ($with)      { $query->with($with); }
        if ($withCount) { $query->withCount($withCount); }

        $isRelationPath = function ($s) use ($query) {
            if (!is_string($s) || strpos($s, '.') === false) return false;
            [$rel] = explode('.', $s, 2);
            return method_exists($query->getModel(), $rel);
        };
        $split = fn ($s) => explode('.', $s, 2);

        foreach ($filterBy as $column => $value) {
            if ($isRelationPath($column)) {
                [$rel, $col] = $split($column);
                $query->whereHas($rel, function ($rq) use ($col, $value) {
                    is_array($value) ? $rq->whereIn($col, $value) : $rq->where($col, $value);
                });
            } else {
                is_array($value) ? $query->whereIn($column, $value) : $query->where($column, $value);
            }
        }

        $from = $this->msToCarbon($params['date_from'] ?? null);
        $to   = $this->msToCarbon($params['date_to'] ?? null);

        if ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        } elseif ($from) {
            $query->where('created_at', '>=', $from);
        } elseif ($to) {
            $query->where('created_at', '<=', $to);
        }

        // Search
        if ($search && !empty($columns)) {
            $cols = (array) $columns;
            $query->where(function ($q) use ($search, $cols, $isRelationPath, $split) {
                foreach ($cols as $col) {
                    if ($isRelationPath($col)) {
                        [$rel, $c] = $split($col);
                        $q->orWhereHas($rel, fn($rq) => $rq->where($c, 'like', "%{$search}%"));
                    } else {
                        $q->orWhere($col, 'like', "%{$search}%");
                    }
                }
            });
        }

        // Sorting
        if ($isRelationPath($sortBy)) {
            [$rel, $col] = $split($sortBy);
            $alias = "sort_{$rel}_" . str_replace('.', '_', $col);
            $query->withAggregate("{$rel} as {$alias}", $col)->orderBy($alias, $sortDir);
        } else {
            $query->orderBy($sortBy, $sortDir);
        }

        return $query;
    }

    protected function msToCarbon($ms): ?Carbon
    {
        if (!$ms) return null;

        return Carbon::createFromTimestampMs((int) $ms);
    }
}