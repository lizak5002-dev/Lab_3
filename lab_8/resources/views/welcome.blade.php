@extends('layouts.app')

@section('title', 'Главная страница')

@section('content')
<div class="container">
    <!-- Центральный блок с ссылками -->
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-6 col-lg-4 text-center">
            <!-- Заголовок -->
            <h1 class="mb-4">
                <i class="fas fa-castle"></i><br>
                Замки Эстонии
            </h1>
            
            <!-- Простые ссылки -->
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    @if (Route::has('login'))
                        <div class="d-grid gap-3">
                            @auth
                                <!-- Для авторизованных -->
                                <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-lg">
                                    <i class="fas fa-tachometer-alt"></i> Дашборд
                                </a>
                                <a href="{{ route('castles.index') }}" class="btn btn-outline-primary btn-lg">
                                    <i class="fas fa-list"></i> Все замки
                                </a>
                            @else
                                <!-- Для гостей -->
                                <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                                    <i class="fas fa-sign-in-alt"></i> Войти
                                </a>
                                
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg">
                                        <i class="fas fa-user-plus"></i> Регистрация
                                    </a>
                                @endif
                                
                                <!-- Гость может смотреть замки -->
                                <a href="{{ route('castles.index') }}" class="btn btn-secondary btn-lg mt-3">
                                    <i class="fas fa-eye"></i> Просмотр замков
                                </a>
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection