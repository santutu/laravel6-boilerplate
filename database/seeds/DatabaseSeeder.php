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
        $admin= \App\Models\User::where('name','admin')->first();
        if(!$admin){
            $user = factory(\App\Models\User::class)->create([
                'name' => 'admin',
                'email' => 'admin@email.com',
                'password'=>Hash::make('admin')
            ]);
            $user->save();
        }

        // $this->call(UsersTableSeeder::class);
    }
}
