<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionRoleSeeder extends Seeder
{
   /**
    * Run the database seeds.
    *
    * @return void
    */
   public function run()
   {
      // Role Admin
      $role_admin = Role::create(['name' => 'admin']);
      $admin_access_index = Permission::create(['name' => 'admin.access.index']);
      $admin_access_profile = Permission::create(['name' => 'admin.access.profile']);
      $admin_access_user = Permission::create(['name' => 'admin.access.user']);
      $admin_access_news = Permission::create(['name' => 'admin.access.news']);
      $admin_access_family = Permission::create(['name' => 'admin.access.family']);

      $role_admin->givePermissionTo($admin_access_index);
      $role_admin->givePermissionTo($admin_access_profile);
      $role_admin->givePermissionTo($admin_access_user);
      $role_admin->givePermissionTo($admin_access_news);
      $role_admin->givePermissionTo($admin_access_family);

      // Role Operator
      $role_operator = Role::create(['name' => 'operator']);

      $role_operator->givePermissionTo($admin_access_index);
      $role_operator->givePermissionTo($admin_access_profile);
      $role_operator->givePermissionTo($admin_access_news);
      $role_operator->givePermissionTo($admin_access_family);

      // Role User
      $role_user = Role::create(['name' => 'user']);
      $user_access_index = Permission::create(['name' => 'user.access.index']);

      $role_user->givePermissionTo($user_access_index);

      $admin = User::create([
         'name' => 'Administrator',
         'email' => 'admin@admin.com',
         'password' => bcrypt('secret'),
      ]);
      $admin->assignRole('admin');

      $operator = User::create([
         'name' => 'Operator',
         'email' => 'operator@email.com',
         'password' => bcrypt('secret'),
      ]);
      $operator->assignRole('operator');

      $user = User::create([
         'name' => 'User Test',
         'email' => 'user@email.com',
         'password' => bcrypt('secret'),
      ]);
      $user->assignRole('user');
   }
}
