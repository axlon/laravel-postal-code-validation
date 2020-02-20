<?php

require_once __DIR__ . '/../vendor/autoload.php';

# The array loader was introduced in Laravel 5.2
if (!class_exists('\Illuminate\Contracts\Translation\Loader\ArrayLoader')) {

    # The Loader interface was named LoaderInterface before Laravel 5.5
    if (!interface_exists('\Illuminate\Contracts\Translation\Loader')) {
        class_alias('\Illuminate\Translation\LoaderInterface', '\Illuminate\Contracts\Translation\Loader');
    }

    require_once __DIR__ . '/Polyfills/ArrayLoader.php';
}
