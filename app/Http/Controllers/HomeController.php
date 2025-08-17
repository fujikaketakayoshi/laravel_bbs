<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ThreadRequest;
use App\Models\Thread;
use App\Models\Reply;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;


class HomeController extends Controller
{
    public function __construct()
    {
    }

    public function index(): View
    {
        $threads = Thread::withCount('replies')->orderBy('created_at', 'desc')->paginate(10);
        return view('index', ['threads' => $threads]);
    }
    
    public function thread_store(ThreadRequest $request): RedirectResponse
    {
        $thread = new Thread();
        $thread->user_id = (int) Auth::id();
        $thread->title = $request->title;
        $thread->body = $request->body;
        $thread->save();
        
        return redirect()->route('index');
    }
    
    public function thread(Thread $thread): View
    {
        if ( $thread->delete_flag == 1) {
            return view('deleted_thread');
        } else {
            return view('thread', ['thread' => $thread]);
        }
    }
    
    public function reply_store(Request $request): RedirectResponse
    {
        $request->validate([
            'body' => 'required',
        ]);

        $reply = new Reply();
        /** @var int $thread_id */
        $thread_id = $request->thread_id;
        /** @var string $body */
        $body = $request->body;
        $reply->thread_id = $thread_id;
        $reply->user_id = (int) Auth::id();
        $reply->body = $body;
        $reply->save();
        
        return redirect(route('thread', $request->thread_id));
    }
    
    public function withdrawal(): View
    {
        return view('withdrawal');
    }
    
    public function withdrawal_done(): RedirectResponse
    {
        $user = User::findOrFail(Auth::id());
        $user->delete();
        return redirect()->route('index');
    }
    
}
