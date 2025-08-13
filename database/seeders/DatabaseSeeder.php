<?php

namespace Database\Seeders;

use App\Http\Controllers\User\complaintcontroller;
use App\Models\Area;
use App\Models\Area_Box;
use App\Models\Complaint;
use App\Models\Counter;
use App\Models\Counter_Box;
use App\Models\ElectricalBox;
use App\Models\Employee;
use App\Models\Feature;
use App\Models\Neighborhood;
use App\Models\Payment;
use App\Models\Phone;
use App\Models\Plan;
use App\Models\Plan_Feature;
use App\Models\PlanPrice;
use App\Models\PowerGenerator;
use App\Models\Spending;
use App\Models\Subscription;
use App\Models\SubscriptionRequest;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Types\ComplaintTypes;
use Faker\Factory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            RoleSeeder::class,
            FaqSeeder::class
        ]);

      $user= User::factory()->create([
           'first_name' => 'Admin',
           'email' => 'huda1812zain@gmail.com',
'password'=>12345678,
//            'role'=>'Admin'
       ]);
        $user->assignRole('superAdmin');

        // Create 10 Users with role 'user'
        $users = User::factory()
            ->count(10)
//            ->state(['role' => 'Customer'])
            ->create();

        // Create 5 Users with role 'powergenerator' and related PowerGenerators
        $generatorUsers = User::factory()
            ->count(5)
//            ->state(['role' => 'PowerGenerator'])
            ->create();

        $generators = collect();
        foreach ($generatorUsers as $user) {
            $generator = PowerGenerator::factory()->for($user)->create();
            $generators->push($generator);

            // Each Generator gets 3 Employees
            Employee::factory()->count(3)->state([
                'generator_id' => $generator->id,
            ])->create();

            // Each Generator gets 2 Phones
            Phone::factory()->count(2)->state([
                'generator_id' => $generator->id,
            ])->create();
        }

        // Create 3 Plans
        $plans = Plan::factory()->count(3)->create();

        // Create 6 Features and randomly attach to plans
        $features = Feature::factory()->count(6)->create();
        foreach ($plans as $plan) {
            $planFeatures = $features->random(rand(2, 4));
            foreach ($planFeatures as $feature) {
                Plan_Feature::factory()->create([
                    'plan_id' => $plan->id,
                    'feature_id' => $feature->id,
                    'value'=>rand(1,20)*50,
                ]);
            }
        }

        // Add pricing to each plan
        foreach ($plans as $plan) {
            PlanPrice::factory()->count(2)->for($plan)->create();
        }

        // Create 10 Neighborhoods with Areas
        $neighborhoods = Neighborhood::factory()->count(10)->create();
        foreach ($neighborhoods as $neighborhood) {
            Area::factory()->count(2)->state([
                'neighborhood_id' => $neighborhood->id,
                'generator_id' => $generators->random()->id,
            ])->create();
        }

        // Create ElectricalBoxes
        $boxes = ElectricalBox::factory()->count(20)
            ->state([
                'generator_id' => $generators->random()->id,
            ])->create();

        // Link Areas to Boxes
        foreach (Area::all() as $area) {
            $randomBoxes = $boxes->random(rand(1, 3));
            foreach ($randomBoxes as $box) {
                Area_Box::factory()->create([
                    'area_id' => $area->id,
                    'box_id' => $box->id,
                ]);
            }
        }

        // Create Counters for users
        foreach ($users as $user) {
            $counter = Counter::factory()->for($user)->create();
            $box = $boxes->random();
            Counter_Box::factory()->create([
                'counter_id' => $counter->id,
                'box_id' => $box->id,
            ]);

            Spending::factory()->count(3)->for($counter)->create();
            Payment::factory()->count(2)->for($counter)->create();
            Complaint::factory()->count(1)->for($counter)->create([
                'type'=>ComplaintTypes::Cut,
                'user_id'=>$users->random()->id,
            ]);

        }
        Complaint::factory()->count(5)->create([
            'type'=>ComplaintTypes::App,
            'user_id'=>$users->random()->id,
        ]);

        $planPrices = PlanPrice::all();
        foreach ($users as $user) {
            SubscriptionRequest::factory()->count(1)->for($user)->create([
                'planPrice_id'=>$planPrices->random()->id,
            ]);
        }


        foreach ($generators as $generator) {
            Subscription::factory()->create([
                'generator_id' => $generator->id,
                'planPrice_id' => $planPrices->random()->id,
            ]);
        }

    }





}
