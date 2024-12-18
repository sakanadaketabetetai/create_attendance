<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalRouteUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'approval_route_id', 'admin_id'
    ];
}
