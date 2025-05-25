<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Faq::insert([
           [ 'question'=>'what is this platform','answer'=>'it will help you track your electricity consumption','role'=>'landing'],
            ['question'=>'How to add my employees','answer'=>'go to your dashboard','role'=>'admin'],
            ['question'=>'how can i make complaintes','answer'=>'go to the section make a report','role'=>'user'],
            ['question'=>'how to change the statues of an electrisity box','answer'=>'go to manage electrical boxes','role'=>'employee'],
        ]);
    }
}
