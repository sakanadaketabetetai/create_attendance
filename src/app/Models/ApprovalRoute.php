<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalRoute extends Model
{
    use HasFactory;
    
    protected $fillable = ['approval_route_name'];


    public function approval_requests(){
        return $this->hasMany(ApprovalRequest::class);
    }

    public function users(){
        return $this->belongsToMany(User::class, 'approval_route_users');
    }
}
