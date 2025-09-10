<?php

namespace App\Services\Admin;

use App\ApiHelper\ApiCode;
use App\Exceptions\ErrorException;
use App\Models\Counter;
use App\Repositories\Eloquent\Admin\CounterBoxRepository;
use App\Repositories\interfaces\Admin\CounterBoxRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CounterBoxService
{
    public function __construct(
        private CounterBoxRepositoryInterface $repository
    ) {}

    public function assignCounter(int $counterId, int $boxId)
    {
        return $this->repository->assignCounterToBox($counterId, $boxId);
    }

    public function removeCounter(int $counterId, int $boxId)
    {
        return $this->repository->removeCounterFromBox($counterId, $boxId);
    }

    public function getCurrentBox(int $counterId)
    {
        return $this->repository->getCurrentBox($counterId);
    }

    public function getBoxCounters(int $boxId)
    {
        return $this->repository->getBoxCounters($boxId);
    }
    public function createCounter(array $data)
    {
//        return DB::transaction(function () use ($data) {
//            $generator = auth()->user()->powerGenerator->id;
//
//            if (!$generator) {
//                throw new ErrorException(__('powerGenerator.noGeneratorForUser'),ApiCode::BAD_REQUEST);
//            }
//
//            $qrCodeData = [
//                'counter_number' => $data['number'],
//                'generator_id' => $generator,
//                'created_at' => now()->toISOString()
//            ];
//
//            $qrCode = $this->generateQRCode($qrCodeData);
//
//
//            $counter = $this->repository->create([
//                'number' => $data['number'],
//                'QRCode' =>  asset($qrCode['url']),
//                'user_id' => $data['user_id'],
//                'generator_id' => $generator,
//                'current_spending' => 0,
//                'box_id' => $data['box_id'] ?? null
//            ]);
//
//            return $counter;
//        });
        return DB::transaction(function () use ($data) {
            $generator = auth()->user()->powerGenerator->id;

            if (!$generator) {
                throw new ErrorException(__('powerGenerator.noGeneratorForUser'), ApiCode::BAD_REQUEST);
            }

            // Generate the counter number first (we need to know the box_id for this)
            $counterNumber = $this->generateCounterNumber($generator, $data['box_id'] ?? null);

            // Generate QR code with the actual counter number
            $qrCodeData = [
                'counter_number' => $counterNumber,
                'generator_id' => $generator,
                'created_at' => now()->toISOString()
            ];

            $qrCode = $this->generateQRCode($qrCodeData);

            // Create the counter
            $counter = $this->repository->create([
                'number' => $counterNumber,
                'QRCode' => asset($qrCode['url']),
                'user_id' => $data['user_id'],
                'generator_id' => $generator,
                'current_spending' => 0
                // Note: box_id is not included as it's not a column in the counters table
            ]);

            // If box_id is provided, assign the counter to the box
            if (isset($data['box_id']) && !is_null($data['box_id'])) {
                $this->assignCounterToBox($counter->id, $data['box_id']);
            }

            return $counter;
        });

    }
    private function generateCounterNumber(int $generatorId, ?int $boxId = null): string
    {
        // Get generator ID (padded to 3 digits)
        $generatorPart = str_pad($generatorId, 3, '0', STR_PAD_LEFT);

        // Get box ID (padded to 3 digits, use '000' if no box)
        $boxPart = $boxId ? str_pad($boxId, 3, '0', STR_PAD_LEFT) : '000';

        // Get the next sequential counter number for this generator+box combination
        $lastCounter = Counter::where('generator_id', $generatorId)
            ->whereHas('electricalBoxes', function($query) use ($boxId) {
                if ($boxId) {
                    $query->where('box_id', $boxId);
                } else {
                    $query->whereNull('box_id');
                }
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
    public function updateCounter($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {

            $counter = $this->repository->find($id);
            $generator = auth()->user()->powerGenerator;

            if (!$generator) {
                throw new ErrorException(__('powerGenerator.noGeneratorForUser'),ApiCode::BAD_REQUEST);
            }
            if (isset($data['number']) && $data['number'] !== $counter->number) {
                $generator = auth()->user()->powerGenerator->id;


                $qrCodeData = [
                    'counter_number' => $data['number'],
                    'generator_id' => $generator,
                    'created_at' => now()->toISOString()
                ];

                $qrCode = $this->generateQRCode($qrCodeData);
                $data['QRCode'] = asset($qrCode['url']);

            }

            $updatedCounter = $this->repository->update($id,$data);
            return $updatedCounter;
        });
    }
    public function deleteCounter($id)
    {
        return $this->repository->deleteCounter($id);
    }

    public function deleteMultipleCounters(array $ids)
    {
        return $this->repository->deleteMultipleCounters($ids);
    }
    public function assignCounterToBox(int $counterId, int $boxId)
    {
        return DB::table('counter__boxes')->updateOrInsert(
            ['counter_id' => $counterId, 'box_id' => $boxId],
            ['removed_at' => null, 'installed_at' => now()]
        );
    }

    private function generateQRCode(array $data)
    {
        $qrContent = json_encode($data);
        $filename = 'qrcodes/counter_' . $data['counter_number'] . '_' . time() . '.svg';


        $qrCode = QrCode::size(300)
            ->format('svg')
            ->generate($qrContent);


        Storage::disk('public')->put($filename, $qrCode);

        return [
            'content' => $qrContent,
            'url' => Storage::url($filename)
        ];
    }
}
