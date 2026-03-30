<?php

namespace App\Services;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use App\Exceptions\BusinessRuleException;

class ReplyService
{    
    public function create(int $thread_id, int $user_id, string $body): Reply
    {
        $thread = Thread::findOrFail($thread_id);
        $user = User::findOrFail($user_id);

        // 削除済みスレ
        if ($thread->delete_flag == 1) {
            throw new BusinessRuleException('削除済みスレッドには返信できません');
        }

        // 凍結ユーザー
        if ($user->is_frozen) {
            throw new BusinessRuleException('凍結中のユーザーは返信できません');
        }

        // 投稿数上限（例：1日30件）
        $todayCount = Reply::where('user_id', $user_id)
            ->whereDate('created_at', today())
            ->count();

        if ($todayCount >= 30) {
            throw new BusinessRuleException('本日の返信上限に達しています');
        }
        
        return Reply::create([
            'thread_id' => $thread_id,
            'user_id' => $user_id,
            'body' => $body,
        ]);
    }

    public function softDelete(int $id): Reply
    {
        $reply = Reply::findOrFail($id);
        $reply->delete_flag = 1;
        $reply->save();

        return $reply;
    }
}