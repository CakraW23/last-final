<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Wallet;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $adminRole =  Role::create(['name' => 'admin']);
        $bankRole =  Role::create(['name' => 'bank']);
        $clientRole =  Role::create(['name' => 'client']);

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
            'role_id' => $adminRole->id,
        ]);

        $bank = User::create([
            'name' => 'Bank',
            'email' => 'bank@gmail.com',
            'password' => bcrypt('bank123'),
            'role_id' => $bankRole->id,
        ]);

        Wallet::create([
            'user_id' => $bank->id,
            'balance' => 0,
            'wallet_number' => fake()->unique()->numerify('##########')
        ]);

        $client = User::create([
            'name' => 'cakra',
            'email' => 'cakra@gmail.com',
            'password' => bcrypt('cakra123'),
            'role_id' => $clientRole->id,
        ]);

        Wallet::create([
            'user_id' => $client->id,
            'balance' => 0,
            'wallet_number' => fake()->unique()->numerify('##########')
        ]);
    }
}
