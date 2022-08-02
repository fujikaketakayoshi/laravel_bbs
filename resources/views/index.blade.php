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
            <div class="card">
                <div class="card-header">スレッド投稿</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('thread_store') }}">
                        @csrf
                        <label for="title" class="col-form-label">件名</label>
                        <input id="title" type="text" class="form-control" name="title" value="{{ old('title') }}">
                        @error('title')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        
                        <label for="body" class="col-form-label">本文</label>
                        <textarea id="body" name="body" class="form-control">{{ old('body') }}</textarea>
                        @error('body')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        
                        <button type="submit" class="btn btn-primary">投稿</button>
                    </form>
                </div>
            </div>
            <br>
            @endauth
            
            @foreach ($threads as $thread)
            <div class="card">
                <div class="card-header">
                    ID:{{ $thread->id }}<br>
                    投稿者：{{ $thread->user->name }}<br>
                    <a href="{{ route('thread', $thread->id) }}">件名：{{ $thread->title }}</a>&nbsp;返信{{ $thread->replies_count }}件
                </div>

                <div class="card-body">
                    {!! nl2br(e($thread->body)) !!}
                </div>
            </div>
            <br>
            @endforeach
        </div>
    </div>
</div>
@endsection
