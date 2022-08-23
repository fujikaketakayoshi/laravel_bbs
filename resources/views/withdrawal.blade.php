@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">退会しますか？</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('withdrawal_done') }}">
                        @csrf
                        <div class="button-center">
                            <button type="submit" class="btn btn-primary">はい</button>
                            <a class="btn btn-primary" href="{{ route('index') }}">いいえ</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
