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
        
        <h2>返信投稿</h2>
        <form action="{{ route('reply_store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">本文</label>
            <textarea class="form-control" name="body"></textarea>
        </div>
            <input type="hidden" class="form-control" name="thread_id" value="{{ $thread->id }}">
            <button type="submit" class="btn btn-primary">投稿</button>
        </form>
        <br>            
        <div class="card">
            <div class="card-header">
            <a href="{{ route('thread', $thread->id) }}"> ThreadID：{{ $thread->id }}</a><br>
            投稿者：{{ $thread->user->name }}<br>
            タイトル：{{ $thread->title }}</div>
            <div class="card-body">
                {{ $thread->body }}
            </div>
        </div>
        @foreach ($thread->replies as $r)
        <div class="card">
            <div class="card-body">
                投稿者：{{ $r->user->name }}<br>
                {{ $r->body }}
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
