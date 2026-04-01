<?php

namespace App\Repositories;

use App\Models\Reply;

class ReplyRepository
{
    public function findById(int $id): Reply
    {
        return Reply::findOrFail($id);
    }

    public function countTodayByUser(int $user_id): int
    {
        return Reply::where('user_id', $user_id)
            ->whereDate('created_at', today())
            ->count();
    }

    public function create(array $data): Reply
    {
        return Reply::create($data);
    }

    public function softDelete(Reply $reply): Reply
    {
        $reply->update([
            'delete_flag' => 1,
        ]);

        return $reply;
    }
}