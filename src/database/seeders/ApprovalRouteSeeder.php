<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ApprovalRoute;

class ApprovalRouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $approval_route = ApprovalRoute::create([
            'approval_route_name' => '勤怠情報承認'
        ]);
    }
}
