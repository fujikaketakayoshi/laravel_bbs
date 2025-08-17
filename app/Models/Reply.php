<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\User;

class Reply extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'thread_id',
        'user_id',
        'body',
        'delete_flag',
    ];

    /**
     * @return BelongsTo<User, Reply>
     */
	public function user(): BelongsTo
    {
		return $this->belongsTo(User::class);
	}
}
