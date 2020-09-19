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

        factory(\App\Models\Partners\Partner::class, 1)->create([
            'is_staff' => true,
        ]);

        factory(\App\Models\Partners\Partner::class, 1)->create([
            'is_staff' => false,
            'is_client' => true,
        ]);
    }
}
