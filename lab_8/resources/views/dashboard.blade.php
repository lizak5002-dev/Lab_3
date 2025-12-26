@extends('layouts.app')

@section('title', 'Личный кабинет')

@section('content')
<div class="container py-4">
    <!-- Заголовок -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>
            <i class="fas fa-tachometer-alt"></i> Личный кабинет
        </h1>
        
        <!-- Информация о пользователе -->
        <div class="text-end">
            <p class="mb-0">Добро пожаловать, <strong>{{ Auth::user()->name }}</strong>!</p>
            <small class="text-muted">{{ Auth::user()->email }}</small>
        </div>
    </div>

    <!-- Карточки с информацией -->
    <div class="row mb-4">
        <!-- Мои замки -->
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">
                        <i class="fas fa-castle"></i> Мои замки
                    </h5>
                    @php
                        $myCastlesCount = Auth::user()->castles()->count();
                    @endphp
                    <p class="display-6">{{ $myCastlesCount }}</p>
                    <a href="{{ route('users.castles', Auth::user()) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-eye"></i> Посмотреть
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Все замки -->
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">
                        <i class="fas fa-list"></i> Все замки
                    </h5>
                    @php
                        $allCastlesCount = App\Models\Castle::count();
                    @endphp
                    <p class="display-6">{{ $allCastlesCount }}</p>
                    <a href="{{ route('castles.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-list"></i> Список
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Профиль -->
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">
                        <i class="fas fa-user"></i> Профиль
                    </h5>
                    <p class="text-muted">
                        {{ Auth::user()->is_admin ? 'Администратор' : 'Пользователь' }}
                    </p>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-edit"></i> Редактировать
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Быстрые действия -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">
                <i class="fas fa-bolt"></i> Быстрые действия
            </h5>
        </div>
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('castles.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Добавить новый замок
                </a>
                <a href="{{ route('castles.index') }}" class="btn btn-primary">
                    <i class="fas fa-list"></i> Все замки
                </a>
                @if(Auth::user()->is_admin)
                    <a href="{{ route('users.index') }}" class="btn btn-info">
                        <i class="fas fa-users"></i> Пользователи
                    </a>
                @endif
                <a href="{{ url('/') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-home"></i> На главную
                </a>
            </div>
        </div>
    </div>
</div>
@endsection