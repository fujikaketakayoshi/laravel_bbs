<?php

namespace App\Services;

use App\Models\Thread;
use App\Repositories\ThreadRepository;
use App\Repositories\UserRepository;

use App\Exceptions\BusinessRuleException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ThreadService
{
    public function __construct(
        private ThreadRepository $threadRepository,
        private UserRepository $userRepository
    ) {}

    public function getThreads(): LengthAwarePaginator
    {
        return $this->threadRepository->paginateLatest();
    }

    public function create(int $user_id, string $title, string $body): Thread
    {
        $user = $this->userRepository->findById($user_id);

        // 凍結ユーザー
        if ($user->is_frozen) {
            throw new BusinessRuleException('凍結中のユーザーは投稿できません');
        }

        // 投稿数上限（例：1日10件）
        $todayCount = $this->threadRepository->countTodayByUser($user_id);

        if ($todayCount >= 10) {
            throw new BusinessRuleException('本日の投稿上限に達しています');
        }

        return $this->threadRepository->create([
            'user_id' => $user_id,
            'title' => $title,
            'body' => $body,
        ]);
    }

    public function softDelete(int $id): void
    {
        $thread = $this->threadRepository->findById($id);
        if ($thread->delete_flag == 1) {
            throw new BusinessRuleException('スレッドは既に削除されています');
        }
        $this->threadRepository->softDelete($thread);
    }
}