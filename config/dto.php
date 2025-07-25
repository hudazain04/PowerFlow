<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | NAMESPACE
    |--------------------------------------------------------------------------
    |
    | The namespace where your DTOs will be created.
    |
    */

    'namespace' => 'App\DTOs',

    /*
    |--------------------------------------------------------------------------
    | REQUIRE CASTING
    |--------------------------------------------------------------------------
    |
    | If this is set to true, you must configure a cast type for all properties of your DTOs.
    | If a property doesn't have a cast type configured it will throw a
    | \WendellAdriel\ValidatedDTO\Exceptions\MissingCastTypeException exception
    |
    */

    'require_casting' => false,
];
