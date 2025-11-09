@extends('layouts.app')

@section('title', 'アカウント作成')

@section('body_class', 'hold-transition register-page')

@section('content')
<div class="register-box">
    <div class="register-logo">
        <a href="{{ url('/') }}"><b>家計簿</b> 1.0</a>
    </div>

    <div class="card">
        <div class="card-body register-card-body">
            <p class="login-box-msg">アカウントを作成</p>

            <form action="{{ route('register') }}" method="POST">
                @csrf
                
                {{-- Name Field --}}
                <div class="input-group mb-3">
                    <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                           placeholder="氏名" value="{{ old('name') }}" required autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                    @error('name')
                        <span class="error invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Email Field --}}
                <div class="input-group mb-3">
                    <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                           placeholder="メールアドレス" value="{{ old('email') }}" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                    @error('email')
                        <span class="error invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                
                {{-- Password Field --}}
                <div class="input-group mb-3">
                    <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                           placeholder="パスワード" required autocomplete="new-password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    @error('password')
                        <span class="error invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Password Confirmation Field --}}
                <div class="input-group mb-3">
                    <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" 
                           placeholder="パスワード確認" required autocomplete="new-password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">作成</button>
                    </div>
                    </div>
            </form>
            
            <p class="mb-0 mt-3">
                <a href="{{ route('login') }}" class="text-center">すでにアカウントをお持ちですか？ ログインする</a>
            </p>
        </div>
        </div>
</div>
@endsection