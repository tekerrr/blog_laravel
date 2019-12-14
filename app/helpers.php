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
