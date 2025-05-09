<?php

namespace App\Models;

use App\Enums\Status;
use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 */
class Task extends Model
{
    /** @use HasFactory<TaskFactory> */
    use HasFactory, SoftDeletes;

    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'ip_address',
        'user_id'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'status' => Status::class,
    ];

    /**
     * @return HasOne
     */
    public function createBy(): HasOne
    {
        return $this->hasOne(User::class, 'user_id');
    }

}
