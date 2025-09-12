<?php

namespace App\ApiHelper;

use App\Models\Counter;

trait GenerateCounterNumber
{
    /**
     * Generate counter number only if box_id is provided
     */
    public function generateCounterNumber(?int $boxId = null): ?string
    {
        if (!$boxId) {
            return null;
        }

        $generatorId = $this->generator_id;

        // Get generator ID (padded to 3 digits)
        $generatorPart = str_pad($generatorId, 3, '0', STR_PAD_LEFT);

        // Get box ID (padded to 3 digits)
        $boxPart = str_pad($boxId, 3, '0', STR_PAD_LEFT);

        // Get the next sequential counter number for this generator+box combination
        $lastCounter = Counter::where('generator_id', $generatorId)
            ->whereHas('electricalBoxes', function($query) use ($boxId) {
                $query->where('box_id', $boxId);
            })
            ->orderBy('id', 'desc')
            ->first();

        $sequentialNumber = 1;

        if ($lastCounter && $lastCounter->number) {
            // Extract the sequential part (last 3 digits)
            $existingSequentialPart = substr($lastCounter->number, -3);
            if (is_numeric($existingSequentialPart)) {
                $sequentialNumber = (int) $existingSequentialPart + 1;
            }
        }

        // Pad sequential number to 3 digits
        $sequentialPart = str_pad($sequentialNumber, 3, '0', STR_PAD_LEFT);

        return $generatorPart . $boxPart . $sequentialPart;
    }
}
