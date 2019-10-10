<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // UserFactoryにadmin stateとして作成
        // DB::table('users')->insert([
        //     'name' => 'John Doe',
        //     'email' => 'contacts@tatsuro.dev',
        //     'email_verified_at' => now(),
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //     'remember_token' => Str::random(10)
        // ]);

        // login用ユーザー
        // $admin = factory(App\User::class)->states('admin')->create();
        factory(App\User::class)->states('admin')->create();

        // その他ダミーユーザー
        // $else = factory(App\User::class, 20)->create();
        factory(App\User::class, 20)->create();

        // dd(get_class($admin)); //"App\User"
        // dd(get_class($else); // "Illuminate\Database\Eloquent\Collection"

        // 作成した全ユーザーのcollection作成
        // $users = $else->concat([$admin]);
        // dd($users->count());
    }
}
