<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalRequest extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'attendance_id', 'approval_route_id', 'approver_status', 'user_id', 'approval_at'
    ];


    public function attendance(){
        return $this->belongsTo(Attendance::class);
    }
    
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function approval_route(){
        return $this->belongsTo(ApprovalRoute::class);
    }
}
