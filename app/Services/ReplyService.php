<?php

namespace App\Services;

use App\Models\Reply;

class ReplyService
{    
    public function create(int $thread_id, int $user_id, string $body): Reply
    {
        return Reply::create([
            'thread_id' => $thread_id,
            'user_id' => $user_id,
            'body' => $body,
        ]);
    }
}