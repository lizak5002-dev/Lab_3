@extends('layouts.app')

@section('title', 'Список замков Эстонии')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>
        <i class="fas fa-castle"></i> Замки Эстонии
        <small class="text-muted">({{ $castles->count() }} замков)</small>
    </h1>
    @if (Auth::check())
    <a href="{{ route('castles.create') }}" class="btn btn-success btn-lg">
        <i class="fas fa-plus"></i> Добавить замок
    </a>
    @else
        <a class="btn btn-success btn-lg disabled">
        <i class="fas fa-plus"></i> Добавить замок
    </a>
    @endif
</div>

<!-- Список замков -->
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    @forelse($castles as $castle)
        <div class="col">
            <div class="card h-100">
                <img src="{{ asset('storage/img/' . $castle->image_filename) }}"
                    class="card-img-top" 
                    alt="{{ $castle->image_alt ?? $castle->name }}">
                
                <div class="card-body">
                    <h5 class="card-title">{{ $castle->name }}</h5>
                    <p class="card-text text-muted">
                        <small>
                            <i class="fas fa-map-marker-alt"></i> {{ $castle->location }}
                        </small>
                    </p>
                    
                    <div class="mb-3">
                        <span class="badge bg-primary">{{ $castle->century_founded }}</span>
                        <span class="badge bg-info">{{ $castle->affiliation }}</span>
                        @if($castle->year_founded)
                            <span class="badge bg-success">{{ $castle->year_founded }} год</span>
                        @endif
                    </div>
                    
                    <p class="card-text">{{ Str::limit($castle->description, 100) }}</p>
                </div>
                
                <div class="card-footer bg-transparent">
                    <div class="d-flex justify-content-between">
                        <!-- ВСЕГДА показываем "Подробнее" -->
                        <a href="{{ route('castles.show', $castle) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i> Подробнее
                        </a>
                        
                        <!-- Кнопки АДМИНА (только если пользователь авторизован И админ) -->
                        @if(Auth::check() && Auth::user()->is_admin)
                            <div class="d-flex">
                                <!-- Кнопка "Восстановить" ТОЛЬКО если замок удален -->
                                @if($castle->trashed())
                                    <form action="{{ route('castles.restore', $castle->id) }}" method="post" class="me-2">
                                        @csrf
                                        <input type="hidden" name="return_url" value="{{ Request::url() }}">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fas fa-undo"></i> Восстановить
                                        </button>
                                    </form>
                                @endif
                                
                                <!-- Кнопка "Удалить навсегда" (для админа) -->
                                <!-- Может быть всегда или только для удаленных - решите что нужно -->
                                <form action="{{ route('castles.purge', $castle->id) }}" method="post">
                                    @csrf
                                    <input type="hidden" name="return_url" value="{{ Request::url() }}">
                                    <button type="submit" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('Удалить навсегда? Это действие нельзя отменить!')">
                                        <i class="fas fa-fire"></i> Удалить навсегда
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle fa-2x mb-3"></i>
                <h4>Замки не найдены</h4>
                <p>Пока нет ни одного замка в базе данных.</p>
                <a href="{{ route('castles.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Добавить первый замок
                </a>
            </div>
        </div>
    @endforelse
</div>
<!-- Toast контейнер -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="downloadToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="fas fa-spinner fa-spin me-2 text-primary"></i>
            <strong class="me-auto">Загрузка</strong>
            <small class="text-muted">только что</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Функционал загрузки временно недоступен. Приносим извинения за неудобства!
        </div>
    </div>
</div>
<!-- Пагинация -->
@if(method_exists($castles, 'hasPages') && $castles->hasPages())
    <div class="mt-4">
        {{ $castles->links() }}
    </div>
@endif
@endsection