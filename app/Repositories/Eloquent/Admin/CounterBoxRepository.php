<?php

namespace App\Repositories\Eloquent\Admin;


use App\Models\Counter_Box;

use App\Models\Counter;
use App\Models\ElectricalBox;

use App\Repositories\interfaces\Admin\CounterBoxRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CounterBoxRepository implements CounterBoxRepositoryInterface
{
    public function assignCounterToBox(int $counterId, int $boxId)
    {
        $boxExists = ElectricalBox::where('id', $boxId)->exists();

        if (!$boxExists) {
            throw new \Exception("Electrical box with ID {$boxId} does not exist");
        }
        return DB::table('counter__boxes')->updateOrInsert(
            ['counter_id' => $counterId, 'box_id' => $boxId],
            ['removed_at' => null, 'installed_at' => now()]
        );
    }

    public function removeCounterFromBox(int $counterId, int $boxId)
    {
        return DB::table('counter__boxes')
            ->where('counter_id', $counterId)
            ->where('box_id', $boxId)
//            ->whereNull('removed_at') do not forget if you make exception you can return this line
            ->update(['removed_at' => now()]);
    }

    public function getCurrentBox(int $counterId)
    {
        return DB::table('counter__boxes')
            ->where('counter_id', $counterId)
            ->whereNull('removed_at')
            ->join('electrical_boxes', 'electrical_boxes.id', '=', 'counter__boxes.box_id')
            ->select('electrical_boxes.*')
            ->first();
    }

    public function getBoxCounters(int $boxId)
    {
        return DB::table('counter__boxes')
            ->where('box_id', $boxId)
            ->whereNull('removed_at')
            ->join('counters', 'counters.id', '=', 'counter__boxes.counter_id')
            ->select('counters.*')
            ->get();
    }
    public function create(array $data)
    {
        return DB::transaction(function ()use($data){
     $counter=Counter::create([
            'number' => $data['number'],
            'QRCode' => $data['QRCode'],
            'user_id' => $data['user_id'],
            'generator_id' => $data['generator_id'],
            'current_spending' => $data['current_spending']
        ]);


            if (array_key_exists('box_id', $data) && !is_null($data['box_id'])) {
                $this->assignCounterToBox($counter->id, $data['box_id']);
            }


        return $counter;
        });
    }
    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $counter = Counter::findOrFail($id);

            $updateData = [
                'number' => $data['number'] ?? $counter->number,
                'user_id' => $data['user_id'] ?? $counter->user_id,
                'current_spending' => $data['current_spending'] ?? $counter->current_spending,
            ];

            if (isset($data['QRCode'])) {
                $updateData['QRCode'] = $data['QRCode'];
            }

            $counter->update($updateData);


            if (array_key_exists('box_id', $data)) {
                DB::table('counter__boxes')
                    ->where('counter_id', $id)->delete();
                $this->assignCounterToBox($counter->id, $data['box_id']);
            }

            return $counter;
        });
    }
    public function find($id)
    {
        return Counter::findOrFail($id);
    }
    public function deleteCounter($id)
    {
        return DB::transaction(function () use ($id) {

            DB::table('counter__boxes')
                ->where('counter_id', $id)->delete();


            $counter = Counter::findOrFail($id);
            return $counter->delete();
        });
    }

    public function deleteMultipleCounters(array $ids)
    {
        return DB::transaction(function () use ($ids) {

            DB::table('counter__boxes')
                ->whereIn('counter_id', $ids)
                ->delete();


            return Counter::whereIn('id', $ids)->delete();
        });
    }


    public function counterCount(int $box_id): int
    {
        $counterCount=Counter_Box::where('box_id',$box_id)->count();
        return $counterCount;
    }
}
