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

if (! function_exists('is_current_route')) {
    /**
     * @param string $name
     * @param null $parameters
     * @return bool
     */
    function is_current_route(string $name, $parameters = null)
    {
        return null === $parameters
            ? request()->route()->named($name)
            : (request()->getUri() === route($name, $parameters));
    }
}
