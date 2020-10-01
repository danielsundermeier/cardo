<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'email' => 'daniel@hof-sundermeier.de',
            'password' => Hash::make('12345678'),
            'name' => 'Daniel',
        ]);

        $user->partner()->create([
            'firstname' => 'Daniel',
            'lastname' => 'Sundermeier',
            'is_staff' => true,
        ]);

        $user = User::create([
            'email' => 'info@cardo-gesundheit.de',
            'password' => Hash::make('cardo-gesundheit'),
            'name' => 'Jule',
        ]);

        $user->partner()->create([
            'firstname' => 'Juliette',
            'lastname' => 'Rolf',
            'is_staff' => true,
        ]);

        // factory(\App\Models\Partners\Partner::class, 5)->create([
        //     'is_staff' => false,
        //     'is_client' => true,
        // ]);
    }
}
