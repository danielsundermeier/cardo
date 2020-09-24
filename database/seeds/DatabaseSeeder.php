<?php

use App\Models\Courses\Course;
use App\Models\Items\Item;
use App\Models\Items\Unit;
use App\Models\Partners\Partner;
use App\Models\Tasks\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);

        $partner = Partner::first();

        $unit = Unit::create([
            'name' => 'Stück',
            'abbreviation' => 'stk',
        ]);

        $course = Course::create([
            'name' => 'Kurs A',
            'partner_id' => $partner->id,
        ]);

        Item::create([
            'course_id' => $course->id,
            'unit_id' => $unit->id,
            'name' => 'Produkt A',
        ]);

        $categories = [
            'Büro & Buchhaltung',
            'Konzepte',
            'Kunden',
            'Marketing',
            'Material',
            'Veranstaltungen',
        ];
        foreach ($categories as $key => $name) {
            Category::create([
                'name' => $name,
            ]);
        }
    }
}
