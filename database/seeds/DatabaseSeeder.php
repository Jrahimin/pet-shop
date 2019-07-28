<?php

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
         $this->call(UserTablePasswordSeeder::class);
        $this->call(AdministratorsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(RoleHasPermissionsTableSeeder::class);
        $this->call(ModelHasRoleTableSeeder::class);
        $this->call(CategoryDarbaiTableSeeder::class);
        $this->call(PaukuotesTableSeeder::class);
        $this->call(SliderOptionSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(PackagesDefaultSeeder::class);
        $this->call(PackagePostionSeeder::class);

    }
}
