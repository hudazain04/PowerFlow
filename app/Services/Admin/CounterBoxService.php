<?php

namespace App\Services\Admin;

use App\ApiHelper\ApiCode;
use App\DTOs\PasswordEmailDTO;
use App\Exceptions\ErrorException;
use App\Models\Counter;
use App\Models\User;
use App\Repositories\Eloquent\Admin\CounterBoxRepository;
use App\Repositories\interfaces\Admin\ActionRepositoryInterface;
use App\Repositories\interfaces\Admin\CounterBoxRepositoryInterface;
use App\Services\User\PasswordResetService;
use App\Types\ActionTypes;
use App\Types\ComplaintStatusTypes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CounterBoxService
{
    public function __construct(
        private CounterBoxRepositoryInterface $repository,
        private PasswordResetService $passwordResetService,
        protected EmployeeAssignmentService $employeeAssignmentService,
        protected ActionRepositoryInterface $actionRepository,
    ) {
    }

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
//                throw new ErrorException(__('powerGenerator.noGeneratorForUser'), ApiCode::BAD_REQUEST);
//            }
//
//            $boxId = $data['box_id'] ?? null;
//
//            // Create a counter instance to use the trait
//            $counterModel = new Counter();
//            $counterModel->generator_id = $generator;
//
//            // Generate counter number only if box_id is provided
//            $counterNumber = $counterModel->generateCounterNumber($boxId);
//
//            // Generate QR code only if we have a counter number
//            $qrCodeUrl = null;
//            if ($counterNumber) {
//                $qrCodeData = [
//                    'counter_number' => $counterNumber,
//                    'generator_id' => $generator,
//                    'created_at' => now()->toISOString()
//                ];
//                $qrCode = $this->generateQRCode($qrCodeData);
//                $qrCodeUrl = asset($qrCode['url']);
//            }
//
//            // Create the counter
//            $counter = $this->repository->create([
//                'number' => $counterNumber,
//                'QRCode' => $qrCodeUrl,
//                'user_id' => $data['user_id'],
//                'generator_id' => $generator,
//                'current_spending' => 0
//            ]);
//
//            // If box_id is provided, assign the counter to the box
//            if ($boxId) {
//                $this->assignCounterToBox($counter->id, $boxId);
//            }
//
//            return $counter;
//        });
        return DB::transaction(function () use ($data) {
            $generator = auth()->user()->powerGenerator->id;

            if (!$generator) {
                throw new ErrorException(__('powerGenerator.noGeneratorForUser'), ApiCode::BAD_REQUEST);
            }

            // Find or create user based on phone number and email
            $user = $this->findOrCreateUser(
                $data['phone_number'],
                $data['email'] ?? null,
                $data['first_name'] ?? null,
                $data['last_name'] ?? null
            );


            $boxId = $data['box_id'] ?? null;

            // Create a counter instance to use the trait
            $counterModel = new Counter();
            $counterModel->generator_id = $generator;

            // Generate counter number
            $counterNumber = $counterModel->generateCounterNumber($boxId);

            // Generate QR code
            $qrCodeData = [
                'counter_number' => $counterNumber,
                'generator_id' => $generator,
                'created_at' => now()->toISOString()
            ];
            $qrCode = $this->generateQRCode($qrCodeData);
            $qrCodeUrl = asset($qrCode['url']);

            // Create the counter
            $counter = $this->repository->create([
                'number' => $counterNumber,
                'QRCode' => $qrCodeUrl,
                'user_id' => $user->id,
                'generator_id' => $generator,
                'current_spending' => 0,
                'physical_device_id'=>$data['physical_device_id'],
            ]);

            // If box_id is provided, assign the counter to the box
            if ($boxId) {
                $this->assignCounterToBox($counter->id, $boxId);
            }
            $action=$this->actionRepository->create([
                'type'=> ActionTypes::SetUp,
                'status'=>ComplaintStatusTypes::Pending,
                'counter_id'=>$counter->id,
                'generator_id'=>$counter->generator_id,
            ]);
//          $this->employeeAssignmentService->assignToAction($action);
            return $counter;
        });

    }
    private function findOrCreateUser(string $phoneNumber, ?string $email = null, ?string $firstName = null, ?string $lastName = null): User
    {
        $normalizedPhone = preg_replace('/[^0-9]/', '', $phoneNumber);
        $user = User::where('phone_number', $normalizedPhone)->first();

        if ($user) {
            // User exists, return the existing user
            return $user;
        }

        // Check if email is already taken by another user
        $existingUserWithEmail = User::where('email', $email)->first();
        if ($existingUserWithEmail) {
            throw new ErrorException('Email already taken by another user', ApiCode::BAD_REQUEST);
        }

        // For new users, first and last name are required
        if (empty($firstName) || empty($lastName) || empty($email)) {
            throw new ErrorException('First name and last name and email are required for new users', ApiCode::BAD_REQUEST);
        }

        $temporaryPassword = Str::random(10);

        // Create user with provided details
        $user = User::create([
            'phone_number' => $normalizedPhone,
            'email' => $email,
            'password' => Hash::make($temporaryPassword),
            'should_reset_password' => true,
            'first_name' => $firstName,
            'last_name' => $lastName
        ]);

        $this->sendPasswordResetEmail($user);

        return $user;
    }

    /**
     * Send password reset email using your existing PasswordResetService
     */
    private function sendPasswordResetEmail(User $user): void
    {
        try {
            // Create DTO for your password reset service
            $dto = new PasswordEmailDTO($user->email);

            // Use your existing PasswordResetService to send the reset link
            $this->passwordResetService->sendLink($dto);

        } catch (\Exception $e) {
            // Log the error but don't fail the counter creation
            Log::error('Failed to send password reset email: ' . $e->getMessage());
        }
    }

    //    public function updateCounter($id, array $data)
//    {
//        return DB::transaction(function () use ($id, $data) {
//
//            $counter = $this->repository->find($id);
//            $generator = auth()->user()->powerGenerator;
//
//            if (!$generator) {
//                throw new ErrorException(__('powerGenerator.noGeneratorForUser'),ApiCode::BAD_REQUEST);
//            }
//            if (isset($data['number']) && $data['number'] !== $counter->number) {
//                $generator = auth()->user()->powerGenerator->id;
//
//
//                $qrCodeData = [
//                    'counter_number' => $data['number'],
//                    'generator_id' => $generator,
//                    'created_at' => now()->toISOString()
//                ];
//
//                $qrCode = $this->generateQRCode($qrCodeData);
//                $data['QRCode'] = asset($qrCode['url']);
//
//            }
//
//            $updatedCounter = $this->repository->update($id,$data);
//            return $updatedCounter;
//        });
//    }
    public function updateCounter($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $counter = $this->repository->find($id);
            $generator = auth()->user()->powerGenerator;

            if (!$generator) {
                throw new ErrorException(__('powerGenerator.noGeneratorForUser'), ApiCode::BAD_REQUEST);
            }

            // Check if box_id is being updated
            $currentBoxId = $this->getCurrentBoxId($counter);
            $newBoxId = $data['box_id'] ?? null;

            // If box_id is being changed, generate new number and QR code
            if (array_key_exists('box_id', $data) && $newBoxId != $currentBoxId) {
                $counterNumber = $this->generateCounterNumberForUpdate($counter, $newBoxId);

                $qrCodeData = [
                    'counter_number' => $counterNumber,
                    'generator_id' => $counter->generator_id,
                    'created_at' => now()->toISOString()
                ];

                $qrCode = $this->generateQRCode($qrCodeData);
                $data['QRCode'] = asset($qrCode['url']);
                $data['number'] = $counterNumber;
            }

            $updatedCounter = $this->repository->update($id, $data);
            return $updatedCounter;
        });
    }

    /**
     * Get the current box ID for a counter
     */
    private function getCurrentBoxId(Counter $counter): ?int
    {
        $currentBox = DB::table('counter__boxes')
            ->where('counter_id', $counter->id)
            ->whereNull('removed_at')
            ->first();

        return $currentBox ? $currentBox->box_id : null;
    }

    /**
     * Generate counter number for update operation
     */
    private function generateCounterNumberForUpdate(Counter $counter, ?int $boxId = null): string
    {
        $generatorId = $counter->generator_id;

        if (!$boxId) {
            // Generate a temporary number when no box is provided
            $lastCounter = Counter::where('generator_id', $generatorId)
                ->whereNot('id', $counter->id) // Exclude current counter
                ->whereNull('box_id')
                ->orderBy('id', 'desc')
                ->first();

            $sequentialNumber = 1;

            if ($lastCounter && $lastCounter->number) {
                // Extract the sequential part (last 6 digits)
                $existingSequentialPart = substr($lastCounter->number, -6);
                if (is_numeric($existingSequentialPart)) {
                    $sequentialNumber = (int) $existingSequentialPart + 1;
                }
            }

            // Pad sequential number to 6 digits
            return 'TEMP' . str_pad($sequentialNumber, 6, '0', STR_PAD_LEFT);
        }

        // Get generator ID (padded to 3 digits)
        $generatorPart = str_pad($generatorId, 3, '0', STR_PAD_LEFT);

        // Get box ID (padded to 3 digits)
        $boxPart = str_pad($boxId, 3, '0', STR_PAD_LEFT);

        // Get the next sequential counter number for this generator+box combination
        $lastCounter = Counter::where('generator_id', $generatorId)
            ->whereNot('id', $counter->id) // Exclude current counter
            ->whereHas('electricalBoxes', function ($query) use ($boxId) {
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

    public function assignBoxToCounter(int $counterId, int $boxId)
    {
        return DB::transaction(function () use ($counterId, $boxId) {
            $counter = Counter::findOrFail($counterId);

            // Generate counter number
            $counterNumber = $counter->generateCounterNumber($boxId);

            // Generate QR code
            $qrCodeData = [
                'counter_number' => $counterNumber,
                'generator_id' => $counter->generator_id,
                'created_at' => now()->toISOString()
            ];
            $qrCode = $this->generateQRCode($qrCodeData);
            $qrCodeUrl = asset($qrCode['url']);

            // Update the counter
            $counter->update([
                'number' => $counterNumber,
                'QRCode' => $qrCodeUrl
            ]);

            // Assign to box
            $this->assignCounterToBox($counterId, $boxId);

            return $counter;
        });
    }

    private function generateQRCode(array $data)
    {
        $qrContent = json_encode($data);
        $filename = 'qrcodes/counter_' . $data['counter_number'] . '_' . time() . '.svg';


        $qrCode = QrCode::size(300)
            ->format('svg')
            ->color(110, 220, 148)
            ->backgroundColor(0, 0, 0, 0)
            ->generate($qrContent);


        Storage::disk('public')->put($filename, $qrCode);

        return [
            'content' => $qrContent,
            'url' => Storage::url($filename)
        ];
    }
}
