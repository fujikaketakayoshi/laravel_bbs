<?php

namespace App\Services;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;

use App\Repositories\ThreadRepository;
use App\Repositories\ReplyRepository;
use App\Repositories\UserRepository;
use App\Exceptions\BusinessRuleException;

class ReplyService
{
    public function __construct(
        private ThreadRepository $threadRepository,
        private ReplyRepository $replyRepository,
        private UserRepository $userRepository
    ) {}

    public function create(int $thread_id, int $user_id, string $body): Reply
    {
        $thread = $this->threadRepository->findById($thread_id);
        $user = $this->userRepository->findById($user_id);

        // 削除済みスレ
        if ($thread->delete_flag == 1) {
            throw new BusinessRuleException('削除済みスレッドには返信できません');
        }

        // 凍結ユーザー
        if ($user->is_frozen) {
            throw new BusinessRuleException('凍結中のユーザーは返信できません');
        }

        // 投稿数上限（例：1日30件）
        $todayCount = $this->replyRepository->countTodayByUser($user_id);
        if ($todayCount >= 30) {
            throw new BusinessRuleException('本日の返信上限に達しています');
        }
        
        return $this->replyRepository->create([
            'thread_id' => $thread_id,
            'user_id' => $user_id,
            'body' => $body,
        ]);
    }

    public function softDelete(int $id): Reply
    {
        $reply = $this->replyRepository->findById($id);
        if ($reply->delete_flag == 1) {
            throw new BusinessRuleException('返信は既に削除されています');
        }
        return $this->replyRepository->softDelete($reply);
    }
}