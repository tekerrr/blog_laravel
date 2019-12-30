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

if (! function_exists('back_with_query')) {
    /**
     * @param array $parameters
     * @return \Illuminate\Http\RedirectResponse
     */
    function back_with_query(array $parameters)
    {
        $query = new \App\Http\Requests\Query();
        $query->set($parameters);
        $routeName = app('router')->getRoutes()->match(request()->create(url()->previous()))->getName();
        return redirect()->route($routeName, $query->get());
    }
}
