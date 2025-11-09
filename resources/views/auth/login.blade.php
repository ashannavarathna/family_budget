@extends('layouts.app')

@section('title', 'ログイン')

{{-- 👈 NEW: AdminLTEのログインページに必要なbodyクラスを定義 --}}
@section('body_class', 'hold-transition login-page ')

@section('content')
<div class="login-box">
    <div class="login-logo">
        <a href="{{ url('/') }}"><b>家計簿</b> 1.0</a>
    </div>
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">アカウントにログイン</p>

            <form action="{{ route('login') }}" method="POST">
                @csrf
                
                {{-- Email Field (AdminLTE input-group format) --}}
                <div class="input-group mb-3">
                    <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                           placeholder="メールアドレス" value="{{ old('email') }}" required autofocus>
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
                           placeholder="パスワード" required autocomplete="current-password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    @error('password')
                        <span class="error invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="row">
                    <div class="col-8">
                        {{-- AdminLTEのicheckスタイリングを使用 --}}
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember">
                                ログイン情報を記憶する
                            </label>
                        </div>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">ログイン</button>
                    </div>
                    </div>
            </form>
            
            <p class="mb-0 mt-3">
                <a href="{{ route('register') }}" class="text-center">アカウントをお持ちでないですか？ 作成する</a>
            </p>
        </div>
        </div>
</div>
@endsection