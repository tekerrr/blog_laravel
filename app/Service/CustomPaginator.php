<?php


namespace App\Service;


use Illuminate\Database\Eloquent\Builder;

class CustomPaginator
{
    protected $queryKey = 'items';
    protected $needed = true;
    protected $perPage;

    public function __construct()
    {
        $perPage = request($this->queryKey);

        if (! $perPage ||  ! in_array($perPage, $this->getSelectorOptions())) {
            $perPage = config('content.custom_paginator.items');
        }

        $this->perPage = $perPage;
    }

    public function getPerPage()
    {
        return $this->perPage;
    }

    public function getQuery(): array
    {
        return [$this->queryKey => $this->perPage];
    }

    public function getSelectorOptions(): array
    {
        return config('content.custom_paginator.options');
    }

    public function paginate(Builder $queryBuilder)
    {
        if ($this->perPage == 'all') {
            return $queryBuilder->get();
        }

        return $this->build($queryBuilder);
    }

    public function isNeed()
    {
        return $this->perPage != 'all';
    }

    public function isDefault()
    {
        return $this->perPage == config('content.custom_paginator.items');
    }

    protected function build(Builder $queryBuilder)
    {
        $queryBuilder = $queryBuilder->paginate($this->perPage);

        if (! $this->isDefault()) {
            $queryBuilder = $queryBuilder->appends(['items' => $this->perPage]);
        }

        return $queryBuilder;
    }
}
