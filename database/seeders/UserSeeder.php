<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\Users\UserService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserService::create('Ms. Mazin Mubarak', 'mazin', 'Mazin@123', Carbon::create(1994, 4, 8));
    }
}
