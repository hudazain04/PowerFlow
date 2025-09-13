<?php

namespace Database\Seeders;

use App\Models\Action;
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
use App\Types\UserTypes;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
//        User::factory(10)->create();

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
            'password' => '123123123',
        ]);
        $user->assignRole('superAdmin');

        // Admin
        $admin = User::factory()->create([
            'first_name' => 'Power generator',
            'email' => 'jawadtakialdeen@gmail.com',
            'password' => '123123123',
        ]);
        $admin->assignRole('admin');

        // Customers
        $users = User::factory()->count(15)->create();
        foreach ($users as $user)
        {
            $user->assignRole(UserTypes::USER);
        }

        // Generator users
        $generatorUsers = User::factory()->count(5)->create();

        $generators = collect();
        $generatorAdmin = PowerGenerator::factory()->for($admin)->create(); // ✅ admin’s generator
        $generators->push($generatorAdmin);

        foreach ($generatorUsers as $user) {
            $generator = PowerGenerator::factory()->for($user)->create();
            $generators->push($generator);

            Phone::factory()->count(2)->create(['generator_id' => $generator->id]);

            GeneratorRequest::factory()->create([
                'user_id' => $users->random()->id,
            ]);

            GeneratorSetting::factory()->create([
                'generator_id' => $generator->id,
            ]);
        }

        // Neighborhoods + Areas
        $areas = collect();
        $neighborhoods = Neighborhood::factory()->count(10)->create();

        // First neighborhood → force admin generator
        $firstNeighborhood = $neighborhoods->shift();
        $adminAreas = Area::factory()->count(2)->create([
            'neighborhood_id' => $firstNeighborhood->id,
            'generator_id' => $generatorAdmin->id,
        ]);
        $areas = $areas->merge($adminAreas);

        // Remaining neighborhoods random generators
        foreach ($neighborhoods as $neighborhood) {
            $created = Area::factory()->count(2)->create([
                'neighborhood_id' => $neighborhood->id,
                'generator_id' => $generators->random()->id,
            ]);
            $areas = $areas->merge($created);
        }

        // Employees
        // Batch for admin generator
        $adminEmployees = Employee::factory()->count(3)->create([
            'generator_id' => $generatorAdmin->id,
            'area_id' => $adminAreas->random()->id,
        ]);

        // Employees for other generators
        foreach ($generators as $generator) {
            Employee::factory()->count(3)->create([
                'generator_id' => $generator->id,
                'area_id' => $areas->random()->id,
            ]);
        }

        // Plans
        $plans = Plan::factory()->count(3)->create();

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

        foreach ($plans as $plan) {
            PlanPrice::factory()->count(2)->for($plan)->create();
        }
        $planPrices = PlanPrice::all();

        // ElectricalBoxes
        $adminBoxes = ElectricalBox::factory()->count(5)->state([
            'generator_id' => $generatorAdmin->id,
        ])->create();

        $boxes = ElectricalBox::factory()->count(15)->state([
            'generator_id' => $generators->random()->id,
        ])->create()->merge($adminBoxes);

        // Link admin boxes to admin areas
        foreach ($adminAreas as $area) {
            Area_Box::factory()->create([
                'area_id' => $area->id,
                'box_id' => $adminBoxes->random()->id,
            ]);
        }

        foreach (Area::all() as $area) {
            foreach ($boxes->random(rand(1, 3)) as $box) {
                Area_Box::factory()->create([
                    'area_id' => $area->id,
                    'box_id' => $box->id,
                ]);
            }
        }

        // Counters
        $counters = collect();

        // Admin counter
        $adminCounter = Counter::factory()->for($users->random())->create([
            'generator_id' => $generatorAdmin->id,
        ]);
        $counters->push($adminCounter);

        Counter_Box::factory()->create([
            'counter_id' => $adminCounter->id,
            'box_id' => $adminBoxes->random()->id,
        ]);

        Spending::factory()->count(3)->for($adminCounter)->create();
        Payment::factory()->count(2)->for($adminCounter)->create();

        Complaint::factory()->for($adminCounter)->create([
            'type' => ComplaintTypes::Cut,
            'user_id' => $users->random()->id,
        ]);

        // Random counters
        foreach ($users as $user) {
            $counter = Counter::factory()->for($user)->create([
                'generator_id' => $generators->random()->id,
            ]);
            $counters->push($counter);

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

        // Subscriptions
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

        AppInfo::factory()->count(3)->create();
        Faq::factory()->count(5)->create();

        $parentAction = Action::factory()->create([
            'employee_id' => $adminEmployees->random()->id,
            'counter_id' => $counters->random()->id,
            'parent_id' => null,
        ]);

        Action::factory()->count(2)->create([
            'employee_id' => $adminEmployees->random()->id,
            'counter_id' => $counters->random()->id,
            'parent_id' => $parentAction->id,
        ]);
    }
}
