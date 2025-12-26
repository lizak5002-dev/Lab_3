@extends('layouts.app')

@section('title', 'Пользователи')

@section('content')
<div class="container mt-4">
    <!-- Заголовок -->
    <h1>Пользователи</h1>
    
    <!-- Сообщение об успехе -->
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            {{ $message }}
        </div>
    @endif
    
    <!-- Список пользователей -->
    <div class="list-group mt-3">
        @foreach($users as $user)
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    {{ $user->name }}
                    <br>
                    <small class="text-muted">{{ $user->email }}</small>
                </div>
                
                <div class="text-end">
                     
                    <!-- Метка админа -->
                    @if($user->is_admin)
                        <span class="badge bg-danger ms-2">Админ</span>
                    @endif
                    <!-- Ссылка на замки пользователя -->
                    <a href="{{ route('users.castles', $user) }}" class="btn btn-sm btn-primary">
                        Замки
                    </a>
                   
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Ссылка назад -->
    <div class="mt-3">
        <a href="{{ route('castles.index') }}" class="btn btn-secondary">
            ← К замкам
        </a>
    </div>
</div>
@endsection