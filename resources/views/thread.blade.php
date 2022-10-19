@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            
            @guest
            <div class="alert alert-info">
                ログインしていないと投稿できません。
            </div>
            @endguest
            
            @auth
            @can('verified')
            @else
            <div class="alert alert-info">
                メール認証していないと投稿できません。
            </div>            
            @endcan
            @endauth

            @can('verified')
            <div class="card">
                <div class="card-header">返信投稿</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('reply_store') }}">
                        @csrf
                        
                        
                        <label for="body" class="col-form-label">本文</label>
                        <textarea id="body" name="body" class="form-control"></textarea>
                        @error('body')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        
                        <input type="hidden" name="thread_id" value="{{ $thread->id }}">
                        <button type="submit" class="btn btn-primary">投稿</button>
                    </form>
                </div>
            </div>
            <br>
            @endcan
            
            <div class="card">
                <div class="card-header">
                    ID:{{ $thread->id }}<br>
                    投稿者：{{ $thread->user->name ?? '退会' }}<br>
                    <a href="{{ route('thread', $thread->id) }}">件名：{{ $thread->title }}</a>
                </div>

                <div class="card-body">
                    {!! nl2br(e($thread->body)) !!}
                </div>
            </div>
            <br>
            @foreach ($thread->replies as $reply)
            <div class="card">
                <div class="card-header">
                    投稿者：{{ $reply->user->name ?? '退会' }}
                </div>
                <div class="card-body">
                 @if ($reply->delete_flag == 1)
                    管理者により削除されました。
                @else
                    {!! nl2br(e($reply->body)) !!}
                @endif
                </div>
                @can('admin')
                    @if ($reply->delete_flag != 1)
                    <div class="card-body">
                        <form action="{{ route('admin.reply_delete') }}" method="post">
                            @method('DELETE')
                            @csrf
                            <input type="hidden" name="id" value="{{ $reply->id }}">
                            <button type="submit" class="btn btn-danger">削除</button>
                        </form>
                    </div>
                    @endif
                @endcan

            </div>
            <br>
            @endforeach
        </div>
    </div>
</div>
@endsection
