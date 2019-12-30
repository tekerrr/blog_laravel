<?php


namespace App\Http\Requests;


class Query
{
    protected $query;

    public function __construct()
    {
        $this->query = request()->query() ?? [];
    }

    public function get($key = null, $default = null)
    {
        if (null === $key) {
            return $this->query;
        }

        return $this->query[$key] ?? $default;
    }

    public function set(array $array)
    {
        $this->query = array_merge($this->query, $array);
    }
}
