<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Thread;
use App\Models\Reply;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
     
    public function __construct()
    {
        //$this->middleware('auth');
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
    
    public function thread_store(Request $request) {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);
#            'title' => 'required|regex:/\A(?!.*?[^\x01-\x7E])(?=.*?[a-z])(?=.*?\d)(?=.*?[!-\/:-@[-`{-~])[!-~]+\z/i',

        
        $thread = new Thread();
        $thread->user_id = Auth::id();
        $thread->title = $request->title;
        $thread->body = $request->body;
        $thread->save();
        
        return redirect(route('index'));
    }
    
    public function thread(Thread $thread) {
        if ( $thread->delete_flag == 1) {
            return view('deleted_thread');
        } else {
            return view('thread', ['thread' => $thread]);
        }
    }
    
    public function reply_store(Request $request) {
        $request->validate([
            'body' => 'required',
        ]);

        $reply = new Reply();
        $reply->thread_id = $request->thread_id;
        $reply->user_id = Auth::id();
        $reply->body = $request->body;
        $reply->save();
        
        return redirect(route('thread', $request->thread_id));
    }
    
    public function withdrawal() {
        return view('withdrawal');
    }
}
