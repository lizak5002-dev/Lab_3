@extends('layouts.app')

@section('title', 'Список замков Эстонии')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>
        <i class="fas fa-castle"></i> Замки Эстонии
        <small class="text-muted">({{ $castles->count() }} замков)</small>
    </h1>
    
    <a href="{{ route('castles.create') }}" class="btn btn-success btn-lg">
        <i class="fas fa-plus"></i> Добавить замок
    </a>
</div>

<!-- Фильтры -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('castles.index') }}" class="row g-3">
            <!-- Фильтр по веку -->
            <div class="col-md-4">
                <label for="century" class="form-label">Век основания</label>
                <select name="century" id="century" class="form-select">
                    <option value="">Все века</option>
                    @forelse($availableCenturies as $century)
                        @php
                            $centuryNumber = (int) filter_var($century, FILTER_SANITIZE_NUMBER_INT);
                        @endphp
                        <option value="{{ $centuryNumber }}" 
                                {{ request('century') == $centuryNumber ? 'selected' : '' }}>
                            {{ $century }}
                        </option>
                    @endforeach
                </select>
                @if(count($availableCenturies) > 0)
                    <div class="form-text">
                        {{ count($availableCenturies) }} вариантов
                    </div>
                @endif
            </div>
            
            <!-- Фильтр по принадлежности -->
            <div class="col-md-4">
                <label for="affiliation" class="form-label">Принадлежность</label>
                <select name="affiliation" id="affiliation" class="form-select">
                    <option value="">Все принадлежности</option>
                    @foreach($availableAffiliations as $affiliation)
                        <option value="{{ $affiliation }}" 
                                {{ request('affiliation') == $affiliation ? 'selected' : '' }}>
                            {{ $affiliation }}
                        </option>
                    @endforeach
                </select>
                @if(count($availableAffiliations) > 0)
                    <div class="form-text">
                        {{ count($availableAffiliations) }} вариантов
                    </div>
                @endif
            </div>
            
            <!-- Кнопки -->
            <div class="col-md-4">
                <!-- Пустой label для выравнивания -->
                <label class="form-label d-md-block d-none" style="visibility: hidden;">Действия</label>
                <div class="d-flex">
                    <button type="submit" class="btn btn-primary me-2 flex-grow-1">
                        <i class="fas fa-filter"></i> Фильтровать
                    </button>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary flex-grow-1">
                        <i class="fas fa-times"></i> Сбросить
                    </a>
                </div>
            </div>
        </form>
    </div>
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
                        <a href="{{ route('castles.show', $castle) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i> Подробнее
                        </a>
                        
                        <div>
                            <a href="{{ route('castles.edit', $castle) }}" class="btn btn-sm btn-outline-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal{{ $castle->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Модальное окно удаления для каждого замка -->
            <div class="modal fade" id="deleteModal{{ $castle->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Удалить замок?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            Вы уверены, что хотите удалить замок "{{ $castle->name }}"?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                            <form action="{{ route('castles.destroy', $castle) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Удалить</button>
                            </form>
                        </div>
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