<?php

namespace App\Domain\Feature\Entities;

class Feature
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public string $name
    )
    {
        //
    }
}
