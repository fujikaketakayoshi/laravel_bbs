<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\Thread;
use App\Models\Reply;

use App\Http\Requests\ThreadDeleteRequest;
use App\Http\Requests\ReplyDeleteRequest;

// use Illuminate\Support\Facades\Auth;
use App\Services\ThreadService;
use App\Services\ReplyService;

use Illuminate\Http\RedirectResponse;

class AdminController extends Controller
{
public function __construct(
        private ThreadService $threadService,
        private ReplyService $replyService
    ) {
    }

    public function thread_delete(ThreadDeleteRequest $request, Thread $thread): RedirectResponse
    {
        $this->threadService->softDelete($thread->id);
        return redirect()->route('index');
    }
    
    public function reply_delete(ReplyDeleteRequest $request, Reply $reply): RedirectResponse
    {        
        $this->authorize('delete', $reply);

        $this->replyService->softDelete($reply->id);
        return redirect()->route('thread', $reply->thread_id);
    }
}
