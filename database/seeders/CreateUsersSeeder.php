<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $users = [
          [
             'name'=>'Admin User',
             'email'=>'admin@email.com',
             'password'=> bcrypt('secret'),
          ],
          [
             'name'=>'Manager User',
             'email'=>'manager@email.com',
             'password'=> bcrypt('secret'),
          ],
          [
             'name'=>'User',
             'email'=>'user@email.com',
             'password'=> bcrypt('secret'),
          ],
      ];

      foreach ($users as $key => $user) {
          User::create($user);
      }
    }
}
