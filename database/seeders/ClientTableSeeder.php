<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ClientTableSeeder extends Seeder
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
                'name'              => 'Sahil Sharma',
                'email'             => 'client@client.com',
                'status'            => '1',
                'email_verified_at' => date('Y-m-d h:m:s'),
                'password'          => Hash::make("Qwerty@123!"),
            ],
        ];

        Client::insert($users);
    }
}
