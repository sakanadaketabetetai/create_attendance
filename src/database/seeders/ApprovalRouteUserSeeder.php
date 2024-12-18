<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ApprovalRouteUser;

class ApprovalRouteUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $approval_route_user = ApprovalRouteUser::create([
            'admin_id' => 1,
            'approval_route_id' => 1,
        ]);
    }
}
