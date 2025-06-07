<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MigrateUsersToCustomersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = DB::table('users')
            ->where('is_admin', false)
            ->get();

        foreach ($users as $user) {
            DB::table('customers')->insert([
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '',
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]);
        }
    }
}
