<?php

use App\Models\Setting;

if (function_exists('setting') === false)
{
    /**
     * Overrides laravels config helper function, allows to load custom configurations from database
     *
     * @param string $key
     * @param mixed $default
     * 
     * @return mixed
     */
    function setting(string $key, $default = null)
    {
        /*
         * Get default environment configuration value
         */
        $value = config($key, $default);

        /*
         * Try to load custom configuration setting from database
         */
        $setting = Setting::get($key);

        /*
         * Return custom or default value
         */
        return $setting ?? $value;
    }
}
