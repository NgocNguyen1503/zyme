<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    const STATUS_ASSIGN = 1;
    const STATUS_CONFIRM = 2;
    const STATUS_DONE = 3;
    const PRIORITY_LOW = 1;
    const PRIORITY_MEDIUM = 2;
    const PRIORITY_HIGH = 3;
    const SYNC = 1;
    const UN_SYNC = 0;

    const CONVERT_STATUS_TXT = [
        1 => 'Assign',
        2 => 'Confirm',
        3 => 'Done'
    ];
    const CONVERT_PRIORITY_TXT = [
        3 => 'High',
        2 => 'Medium',
        1 => 'Low'
    ];
    const CONVERT_SYNCHRONIZE_TXT = [
        1 => 'Synchronized',
        0 => 'Not synchronized'
    ];

    // Liên kết tới bảng CSDL
    protected $table = 'files';

    // Các trường trong bảng CSDL
    protected $fillable = [
        'filename', 
        'deadline', 
        'status', // 1: assign, 2: confirm, 3: done
        'priority', // 1: low, 2: medium, 3: high
        'user_id',
        'synchronize', // 1: synchronized, 0: not synchronized
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}