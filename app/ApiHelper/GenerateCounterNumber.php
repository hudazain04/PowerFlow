<?php
//
//namespace App\ApiHelper;
//
//use App\Models\Counter;
//
//trait GenerateCounterNumber
//{
//    public static function bootGenerateCounterNumber()
//    {
//        static::creating(function ($model) {
//            $model->generateCounterNumber();
//        });
//    }
//
//    protected function generateCounterNumber()
//    {
//        // Get generator ID (padded to 3 digits)
//        $generatorPart = str_pad($this->generator_id, 3, '0', STR_PAD_LEFT);
//
//        // Get box ID (padded to 3 digits, use '000' if no box)
//        $boxPart = $this->box_id ? str_pad($this->box_id, 3, '0', STR_PAD_LEFT) : '000';
//
//        // Get the next sequential counter number for this generator+box combination
//        $lastCounter = Counter::where('generator_id', $this->generator_id)
//            ->when($this->box_id, function ($query) {
//                $query->where('box_id', $this->box_id);
//            })
//            ->orderBy('id', 'desc')
//            ->first();
//
//        if ($lastCounter && $lastCounter->number) {
//            // Extract the sequential part (last 3 digits)
//            $existingSequentialPart = substr($lastCounter->number, -3);
//            if (is_numeric($existingSequentialPart)) {
//                $sequentialNumber = (int) $existingSequentialPart + 1;
//            } else {
//                $sequentialNumber = 1;
//            }
//        } else {
//            $sequentialNumber = 1;
//        }
//
//        // Pad sequential number to 3 digits
//        $sequentialPart = str_pad($sequentialNumber, 3, '0', STR_PAD_LEFT);
//
//        // Set the counter number
//        $this->number = $generatorPart . $boxPart . $sequentialPart;
//    }
//}
