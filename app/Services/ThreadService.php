<?php

namespace App\Services;

use App\Models\Thread;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ThreadService
{
    public function getThreads(): LengthAwarePaginator
    {
        return Thread::withCount('replies')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function create(int $user_id, string $title, string $body): Thread
    {
        return Thread::create([
            'user_id' => $user_id,
            'title' => $title,
            'body' => $body,
        ]);
    }

    public function isDeleted(Thread $thread): bool
    {
        return $thread->delete_flag == 1;
    }
}