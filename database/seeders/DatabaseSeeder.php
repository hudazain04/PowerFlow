<?php

namespace Database\Seeders;

use App\Models\AppInfo;
use App\Models\Area;
use App\Models\Area_Box;
use App\Models\Complaint;
use App\Models\Counter;
use App\Models\Counter_Box;
use App\Models\CustomerRequest;
use App\Models\ElectricalBox;
use App\Models\Employee;
use App\Models\Faq;
use App\Models\Feature;
use App\Models\GeneratorRequest;
use App\Models\GeneratorSetting;
use App\Models\Neighborhood;
use App\Models\Payment;
use App\Models\Phone;
use App\Models\Plan;
use App\Models\Plan_Feature;
use App\Models\PlanPrice;
use App\Models\PowerGenerator;
use App\Models\Spending;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\SubscriptionRequest;
use App\Models\User;
use App\Types\ComplaintTypes;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory(10)->create();

        $this->call([
            RoleSeeder::class,
            FaqSeeder::class,
            AppInfoSeeder::class,
            PermissionSeeder::class,

        ]);

        // Super admin
        $user = User::factory()->create([
            'first_name' => 'Super admin',
            'email' => 'huda1812zain@gmail.com',
            "password" => "123123123",
        ]);
        $user->assignRole('superAdmin');

        // Admin
        $admin = User::factory()->create([
            'first_name' => 'Power generator',
            'email' => 'jawadtakialdeen@gmail.com',
            "password" => "123123123",
        ]);
        $admin->assignRole('admin');

        // Customers
        $users = User::factory()->count(10)->create();

        // Generator users
        $generatorUsers = User::factory()->count(5)->create();

        $generators = collect();
        foreach ($generatorUsers as $user) {
            $generator = PowerGenerator::factory()->for($user)->create();
            $generators->push($generator);

            Employee::factory()->count(3)->create(['generator_id' => $generator->id]);
            Phone::factory()->count(2)->create(['generator_id' => $generator->id]);

            // ✅ Add GeneratorRequest
            GeneratorRequest::factory()->create([
                'user_id'=>$users->random()->id,
            ]);

            // ✅ Add GeneratorSetting
            GeneratorSetting::factory()->create([
                'generator_id' => $generator->id,
            ]);
        }

        // Plans
        $plans = Plan::factory()->count(3)->create();

        // Features + plan features
        $features = Feature::factory()->count(6)->create();
        foreach ($plans as $plan) {
            $planFeatures = $features->random(rand(2, 4));
            foreach ($planFeatures as $feature) {
                Plan_Feature::factory()->create([
                    'plan_id' => $plan->id,
                    'feature_id' => $feature->id,
                    'value' => rand(1, 20) * 50,
                ]);
            }
        }

        // Plan prices
        foreach ($plans as $plan) {
            PlanPrice::factory()->count(2)->for($plan)->create();
        }
        $planPrices = PlanPrice::all();

        // Neighborhoods + Areas
        $neighborhoods = Neighborhood::factory()->count(10)->create();
        foreach ($neighborhoods as $neighborhood) {
            Area::factory()->count(2)->create([
                'neighborhood_id' => $neighborhood->id,
                'generator_id' => $generators->random()->id,
            ]);
        }

        // ElectricalBoxes
        $boxes = ElectricalBox::factory()->count(20)->state([
            'generator_id' => $generators->random()->id,
        ])->create();

        foreach (Area::all() as $area) {
            foreach ($boxes->random(rand(1, 3)) as $box) {
                Area_Box::factory()->create([
                    'area_id' => $area->id,
                    'box_id' => $box->id,
                ]);
            }
        }

        // Counters + Spendings + Payments + Complaints + CustomerRequests
        foreach ($users as $user) {
            $counter = Counter::factory()->for($user)->create([
                'generator_id' => $generators->random()->id,
            ]);

            $box = $boxes->random();
            Counter_Box::factory()->create([
                'counter_id' => $counter->id,
                'box_id' => $box->id,
            ]);

            Spending::factory()->count(3)->for($counter)->create();
            Payment::factory()->count(2)->for($counter)->create();

            Complaint::factory()->for($counter)->create([
                'type' => ComplaintTypes::Cut,
                'user_id' => $users->random()->id,
            ]);

            // ✅ Add CustomerRequest
            CustomerRequest::factory()->create([
                'user_id' => $user->id,
                'box_id' => $box->id,
                'generator_id' => $counter->generator_id,
            ]);
        }

        Complaint::factory()->count(5)->create([
            'type' => ComplaintTypes::App,
            'user_id' => $users->random()->id,
        ]);

        // Subscription Requests
        foreach ($users as $user) {
            SubscriptionRequest::factory()->for($user)->create([
                'planPrice_id' => $planPrices->random()->id,
            ]);
        }

        // Subscriptions + Payments
        foreach ($generators as $generator) {
            $subscription = Subscription::factory()->create([
                'generator_id' => $generator->id,
                'planPrice_id' => $planPrices->random()->id,
            ]);

            SubscriptionPayment::factory(rand(1, 2))->create([
                'subscriptionRequest_id' => $subscription->id,
                'user_id' => $users->random()->id,
                'amount' => rand(100, 500),
            ]);
        }

        // ✅ AppInfo entries
        AppInfo::factory()->count(3)->create();

        // ✅ Faq entries
        Faq::factory()->count(5)->create();
    }
}
