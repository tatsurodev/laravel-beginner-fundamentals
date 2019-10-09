<?php

use Illuminate\Database\Seeder;
// use  Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        // UserFactoryにadmin stateとして作成
        // DB::table('users')->insert([
        //     'name' => 'John Doe',
        //     'email' => 'contacts@tatsuro.dev',
        //     'email_verified_at' => now(),
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //     'remember_token' => Str::random(10)
        // ]);

        // login用
        factory(App\User::class)->states('admin')->create();

        // UserFactory
        factory(App\User::class, 20)->create();
    }
}
