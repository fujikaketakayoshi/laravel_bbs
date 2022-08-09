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

            <div class="card">
                <div class="card-header">
                    件名：削除
                </div>

                <div class="card-body">
                   管理者により削除されました。
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
