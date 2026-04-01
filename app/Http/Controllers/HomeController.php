<?php

namespace App\Http\Controllers;

use App\Http\Requests\ThreadRequest;
use App\Http\Requests\ReplyRequest;
use Illuminate\Support\Facades\Auth;

use App\Models\Thread;

use App\Services\ThreadService;
use App\Services\ReplyService;
use App\Services\UserService;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

use App\Exceptions\BusinessRuleException;

class HomeController extends Controller
{
    private ThreadService $threadService;
    private ReplyService $replyService;
    private UserService $userService;

    public function __construct(
        ThreadService $threadService,
        ReplyService $replyService,
        UserService $userService
    )
    {
        $this->threadService = $threadService;
        $this->replyService = $replyService;
        $this->userService = $userService;
    }

    public function index(): View
    {
        $threads = $this->threadService->getThreads();
        return view('index', ['threads' => $threads]);
    }
    
    public function thread_store(ThreadRequest $request): RedirectResponse
    {
        try {
            $this->threadService->create(
                (int) Auth::id(),
                $request->title,
                $request->body
            );

            return redirect()->route('index');

        } catch (BusinessRuleException $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'business' => $e->getMessage(),
                ]);
        }
    }
    
    public function thread(Thread $thread): View
    {
        if ($thread->delete_flag == 1) {
            return view('deleted_thread');
        }

        return view('thread', ['thread' => $thread]);
    }

    public function reply_store(ReplyRequest $request): RedirectResponse
    {
        $request->validate([
            'body' => 'required',
        ]);
        try {
            $this->replyService->create(
                (int) $request->thread_id,
                (int) Auth::id(),
                $request->body,
            );
            
            return redirect(route('thread', $request->thread_id));
        } catch (BusinessRuleException $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'business' => $e->getMessage(),
                ]);
        }
    }
    
    public function withdrawal(): View
    {
        return view('withdrawal');
    }
    
    public function withdrawal_done(): RedirectResponse
    {
        $user = Auth::user();
        $this->authorize('delete', $user);

        $this->userService->deleteUser($user->id);
        return redirect()->route('index');
    }
    
}
