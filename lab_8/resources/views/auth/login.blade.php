@extends('layouts.app')

@section('title', 'Вход в систему')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white text-center">
                    <h4 class="mb-0">
                        <i class="fas fa-sign-in-alt"></i> Вход в систему
                    </h4>
                </div>
                
                <div class="card-body p-4">
                    <!-- Сообщения об ошибках -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <!-- Успешные сообщения -->
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i> Email адрес
                            </label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autofocus
                                   placeholder="ваш@email.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Пароль -->
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock"></i> Пароль
                            </label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required
                                   placeholder="••••••••">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Запомнить меня -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" 
                                   class="form-check-input" 
                                   id="remember" 
                                   name="remember">
                            <label class="form-check-label" for="remember">
                                Запомнить меня
                            </label>
                        </div>

                        <!-- Кнопки -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt"></i> Войти
                            </button>
                            
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-user-plus"></i> Нет аккаунта? Зарегистрироваться
                                </a>
                            @endif
                            
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="btn btn-link text-decoration-none">
                                    <i class="fas fa-key"></i> Забыли пароль?
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Ссылка на главную -->
            <div class="text-center mt-3">
                <a href="{{ url('/') }}" class="text-decoration-none">
                    <i class="fas fa-arrow-left"></i> Вернуться на главную
                </a>
            </div>
        </div>
    </div>
</div>
@endsection