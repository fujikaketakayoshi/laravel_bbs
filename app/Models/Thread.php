<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Thread extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'title',
        'body',
        'delete_flag',
    ];
	
    /**
     * @return BelongsTo<User, Thread>
     */
	public function user(): BelongsTo
    {
		return $this->belongsTo(User::class);
	}
	
    /**
     * @return HasMany<Reply>
     */
	public function replies(): HasMany
    {
		return $this->hasMany(Reply::class);
	}
	
}
