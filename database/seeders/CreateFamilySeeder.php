<?php

namespace Database\Seeders;

use App\Models\Family;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateFamilySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $Budi = Family::create([
         'name' => "Budi",
         'sex' => 'l'
      ]);
      $Dedi = Family::create([
         'name' => "Dedi",
         'sex' => 'l',
         'parent_id' => $Budi->id
      ]);
      $Dodi = Family::create([
         'name' => "Dodi",
         'sex' => 'l',
         'parent_id' => $Budi->id
      ]);
      $Dede = Family::create([
         'name' => "Dede",
         'sex' => 'l',
         'parent_id' => $Budi->id
      ]);
      $Dewi = Family::create([
         'name' => "Dewi",
         'sex' => 'p',
         'parent_id' => $Budi->id
      ]);
      $Feri = Family::create([
         'name' => "Feri",
         'sex' => 'l',
         'parent_id' => $Dedi->id
      ]);
      $Farah = Family::create([
         'name' => "Farah",
         'sex' => 'p',
         'parent_id' => $Dedi->id
      ]);
      $Gugus = Family::create([
         'name' => "Gugus",
         'sex' => 'l',
         'parent_id' => $Dodi->id
      ]);
      $Gandi = Family::create([
         'name' => "Gandi",
         'sex' => 'l',
         'parent_id' => $Dodi->id
      ]);
      $Hani = Family::create([
         'name' => "Hani",
         'sex' => 'p',
         'parent_id' => $Dede->id
      ]);
      $Hana = Family::create([
         'name' => "Hana",
         'sex' => 'p',
         'parent_id' => $Dede->id
      ]);

    }
}
