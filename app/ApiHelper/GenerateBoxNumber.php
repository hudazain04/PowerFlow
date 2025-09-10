<?php

namespace App\ApiHelper;

use App\Models\ElectricalBox;

trait GenerateBoxNumber
{
    public static function bootGenerateBoxNumber()
    {
        static::creating(function ($model) {
            $model->generateBoxNumber();
        });
    }

    protected function generateBoxNumber()
    {
        // Get generator ID (padded to 3 digits)
        $generatorPart = str_pad($this->generator_id, 3, '0', STR_PAD_LEFT);

        // Get the next sequential number for this generator
        $lastBox = ElectricalBox::where('generator_id', $this->generator_id)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastBox && is_numeric(substr($lastBox->number, -3))) {
            $sequentialNumber = (int) substr($lastBox->number, -3) + 1;
        } else {
            $sequentialNumber = 1;
        }

        // Pad sequential number to 3 digits
        $sequentialPart = str_pad($sequentialNumber, 3, '0', STR_PAD_LEFT);

        // Set the box number
        $this->number = $generatorPart . $sequentialPart;
    }
}
