<?php

namespace App\Repositories;

use App\Models\Thread;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ThreadRepository
{
    public function paginateLatest(): LengthAwarePaginator
    {
        return Thread::withCount('replies')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function countTodayByUser(int $userId): int
    {
        return Thread::where('user_id', $userId)
            ->whereDate('created_at', today())
            ->count();
    }

    public function create(array $data): Thread
    {
        return Thread::create($data);
    }

    public function findById(int $id): Thread
    {
        return Thread::findOrFail($id);
    }

    public function softDelete(Thread $thread): void
    {
        $thread->update([
            'delete_flag' => 1,
        ]);
    }
}