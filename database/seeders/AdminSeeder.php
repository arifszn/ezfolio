<?php

namespace Database\Seeders;

use App\Services\Contracts\AdminContract;
use Constants;
use Exception;
use Illuminate\Database\Seeder;
use Log;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $adminContract = resolve(AdminContract::class);

            $result = $adminContract->handleSignup([
                'email' => 'swazan.arif@gmail.com',
                'password' => '12345',
                'password_confirmation' => '12345',
            ]);

            if ($result['status'] !== Constants::STATUS_CODE_SUCCESS) {
                throw new Exception($result['message']);
            } else {
                $admin = $result['payload'];
            }

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }
}
