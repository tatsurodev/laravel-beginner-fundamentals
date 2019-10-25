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

        // 必要なuser数をask、terminalから取得。askの第二引数でユーザーが未入力だった場合のdefault値指定。得られる値はstringなのでintに変換。0を入力されると困るので最低1user作成
        $usersCount = max((int) $this->command->ask('How many users would you like? min is 1.', 20), 1);


        // admin用ユーザー作成、states methodで複数のstateを指定できる
        // $admin = factory(App\User::class)->states('admin')->create();
        factory(App\User::class)->states('admin')->create();

        // その他ダミーユーザー
        // $else = factory(App\User::class, 20)->create();
        factory(App\User::class, $usersCount)->create();

        // dd(get_class($admin)); //"App\User"
        // dd(get_class($else); // "Illuminate\Database\Eloquent\Collection"

        // 作成した全ユーザーのcollection作成
        // $users = $else->concat([$admin]);
        // dd($users->count());
    }
}
