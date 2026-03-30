<?php

namespace App\Services;

use App\Models\Thread;
use App\Models\User;
use App\Exceptions\BusinessRuleException;
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
        $user = User::findOrFail($user_id);

        // 凍結ユーザー
        if ($user->is_frozen) {
            throw new BusinessRuleException('凍結中のユーザーは投稿できません');
        }

        // 投稿数上限（例：1日10件）
        $todayCount = Thread::where('user_id', $user_id)
            ->whereDate('created_at', today())
            ->count();

        if ($todayCount >= 10) {
            throw new BusinessRuleException('本日の投稿上限に達しています');
        }

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

    public function softDelete(int $id): void
    {
        $thread = Thread::findOrFail($id);
        $thread->delete_flag = 1;
        $thread->save();
    }
}