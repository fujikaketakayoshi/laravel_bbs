<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Thread;
use App\Models\Reply;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;


class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $threads = Thread::withCount('replies')->get();
        return view('index', ['threads' => $threads]);
    }
    
    public function thread_delete(Request $request): RedirectResponse
    {
        $request->validate([
            'id' => 'required|integer',
        ]);
        
        /** @var \App\Models\Thread $thread */
        $thread = Thread::find($request->id);
        $thread->delete_flag = 1;
        $thread->save();
        return redirect()->route('admin.index');
    }
    
    public function reply_delete(Request $request): RedirectResponse
    {
        $request->validate([
            'id' => 'required|integer',
        ]);
        
        /** @var \App\Models\Reply $reply */
        $reply = Reply::find($request->id);
        $reply->delete_flag = 1;
        $reply->save();
        return redirect()->route('thread', $reply->thread_id);
    }
}
