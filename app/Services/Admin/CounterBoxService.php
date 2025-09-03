<?php

namespace App\Services\Admin;

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
        return DB::transaction(function () use ($data) {
            $generator = auth()->user()->powerGenerator->id;

            if (!$generator) {
                throw new \Exception('Authenticated user is not associated with a power generator');
            }

            $qrCodeData = [
                'counter_number' => $data['number'],
                'generator_id' => $generator,
                'created_at' => now()->toISOString()
            ];

            $qrCode = $this->generateQRCode($qrCodeData);


            $counter = $this->repository->create([
                'number' => $data['number'],
                'QRCode' => $qrCode['content'],
                'user_id' => $data['user_id'],
                'generator_id' => $generator,
                'current_spending' => 0,
                'box_id' => $data['box_id'] ?? null
            ]);

            return [
                'counter' => $counter,
                'qr_code_url' => $qrCode['url']
            ];
        });
    }
    public function updateCounter($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {

            $counter = $this->repository->find($id);
            $generator = auth()->user()->powerGenerator;

            if (!$generator) {
                throw new \Exception('Authenticated user is not associated with a power generator');
            }
            if (isset($data['number']) && $data['number'] !== $counter->number) {
                $generator = auth()->user()->powerGenerator->id;


                $qrCodeData = [
                    'counter_number' => $data['number'],
                    'generator_id' => $generator,
                    'created_at' => now()->toISOString()
                ];

                $qrCode = $this->generateQRCode($qrCodeData);
                $data['QRCode'] = $qrCode['content'];
            }

            $updatedCounter = $this->repository->update($id,$data);

            return [
                'counter' => $updatedCounter,
                'qr_code_url' => $qrCode['url'] ?? null
            ];
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
