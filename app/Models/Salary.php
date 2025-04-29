<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    const STATUS_PAID = 1;
    const STATUS_UN_PAID = 0;
    const BASE_SALARY = 50000;
    const CONVERT_PAID_STATUS = [
        1 => '有料',
        0 => '未払い'
    ];

    protected $table = 'salary';

    protected $fillable = [
        'user_id',
        'status', // 1: paid, 0: unpaid
        'salary',
        'month',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
