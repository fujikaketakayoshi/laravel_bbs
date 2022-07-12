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
        <h2>スレッド投稿</h2>
        <form action="{{ route('thread_store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">タイトル</label>
            <input type="text" class="form-control" name="title">
        </div>
        <div class="mb-3">
            <label for="title" class="form-label">本文</label>
            <textarea class="form-control" name="body"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">投稿</button>
        </form>
        <br>
        @foreach ($threads as $t)                  
            <div class="card">
                <div class="card-header">
                <a href="{{ route('thread', $t->id) }}"> ThreadID：{{ $t->id }}</a><br>
                投稿者：{{ $t->user->name }}<br>
                タイトル：{{ $t->title }}</div>
                <div class="card-body">
                    {{ $t->body }}
                </div>
            </div>
        @endforeach
        </div>
    </div>
</div>
@endsection
