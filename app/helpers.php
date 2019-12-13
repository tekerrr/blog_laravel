<?php

if (! function_exists('flash')) {
    /**
     * @param string $message
     * @param string $type
     */
    function flash(string $message, string $type = 'success')
    {
        session()->flash('message', $message);
        session()->flash('message_type', $type);
    }
}

if (! function_exists('routeOrNull')) {
    /**
     * @param string $name
     * @param $model
     * @return string|null
     */
    function routeOrNull(string $name, $model)
    {
        return $model !== null ? route($name, $model) : null;
    }
}
