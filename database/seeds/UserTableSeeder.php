<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->insert(array (
            0 =>[
                'id'                =>  1,
                'name'              =>  'rami badran',
                'username'          =>  'rami',
                'email'             =>  'ramibadran.82@gmail.com',
                'password'          =>  '$2y$10$iGYGdh5HlmMKAiBvSTXLeu12TrlqlxVvyt5wmJgy61Fz1RLXVBBJq',
                'status'            =>  '1',
                'ip_white_list'     =>  '127.0.0.1',
                'api_ip_white_list' =>  '127.0.0.1',
                'secret_key'        =>  '1234567887654321',
                'email_verified_at' =>  '2019-11-11',
                'created_at'        =>  '2019-11-11',
                'updated_at'        =>  '2019-11-11',
                'public_key'        =>  '1234567887654321',
                'private_key'       =>  '1234567887654321',
                'remember_token'    =>  '1',
            ]
        ));
    }
}
