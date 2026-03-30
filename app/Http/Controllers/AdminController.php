<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

    public function thread_delete(ThreadDeleteRequest $request): RedirectResponse
    {
        $this->threadService->softDelete($request->id);
        return redirect()->route('index');
    }
    
    public function reply_delete(ReplyDeleteRequest $request): RedirectResponse
    {        
        $reply = $this->replyService->softDelete($request->id);
        return redirect()->route('thread', $reply->thread_id);
    }
}
