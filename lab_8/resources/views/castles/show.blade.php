@extends('layouts.app')

@section('title', $castle->name)

@section('content')
<div class="row">
    <!-- Основная информация -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0">
                    <i class="fas fa-castle"></i> {{ $castle->name }}
                    <small class="text-muted">({{ $castle->full_name }})</small>
                </h3>
            </div>
            
            <div class="card-body">
                @if($castle->image_preview)
                    <div class="text-center mb-4">
                        <img 
                            src="{{ Storage::url($castle->image_preview) }}" 
                            alt="{{ $castle->image_alt ?? $castle->name }}" 
                            class="img-fluid rounded"
                            style="max-height: 400px;"
                            data-bs-toggle="modal" 
                            data-bs-target="#imageModal"
                            role="button"
                        >
                        <p class="text-muted mt-2">
                            <small>Нажмите на изображение для увеличения</small>
                        </p>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <h5><i class="fas fa-info-circle"></i> Основная информация</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span><strong>Век основания:</strong></span>
                                <span class="badge bg-primary">{{ $castle->century_founded }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span><strong>Год основания:</strong></span>
                                <span>{{ $castle->year_founded ?? 'Не указан' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span><strong>Возраст:</strong></span>
                                <span class="badge bg-success">{{ $castle->age }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span><strong>Месторасположение:</strong></span>
                                <span><i class="fas fa-map-marker-alt text-danger"></i> {{ $castle->location }}</span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="col-md-6">
                        <h5><i class="fas fa-landmark"></i> Исторические данные</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span><strong>Принадлежность:</strong></span>
                                <span class="badge bg-info">{{ $castle->affiliation }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span><strong>Владелец:</strong></span>
                                <span>{{ $castle->owner }}</span>
                            </li>
                            <li class="list-group-item">
                                <span><strong>Slug:</strong></span>
                                <code>{{ $castle->slug }}</code>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Описание -->
                <div class="mt-4">
                    <h5><i class="fas fa-file-alt"></i> Описание</h5>
                    <div class="card card-body bg-light">
                        {!! nl2br(e($castle->description)) !!}
                    </div>
                </div>

                <!-- Мета-информация -->
                <div class="mt-4 text-muted">
                    <small>
                        <i class="fas fa-calendar-plus"></i> Создан: {{ $castle->created_at }}
                        | 
                        <i class="fas fa-calendar-check"></i> Обновлен: {{ $castle->updated_at }}
                    </small>
                </div>
            </div>
            
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('castles.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Назад к списку
                    </a>
                    
                    <div>
                        <a href="{{ route('castles.edit', $castle) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Редактировать
                        </a>
                        
                        <!-- Кнопка удаления с подтверждением -->
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash"></i> Удалить
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Боковая панель с действиями -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt"></i> Быстрые действия</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('castles.create') }}" class="btn btn-success btn-lg w-100 mb-3">
                    <i class="fas fa-plus"></i> Добавить новый замок
                </a>
                
                <div class="list-group">
                    <a href="{{ route('castles.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-list"></i> Все замки
                    </a>
                    <a href="{{ route('castles.edit', $castle) }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-edit"></i> Редактировать этот замок
                    </a>
                    <button type="button" class="list-group-item list-group-item-action text-danger" 
                            data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="fas fa-trash"></i> Удалить этот замок
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Статистика -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Статистика</h5>
            </div>
            <div class="card-body">
                <p>Всего замков в базе: <strong>{{ App\Models\Castle::count() }}</strong></p>
                <p>Замков {{ $castle->century_founded }}: 
                    <strong>{{ App\Models\Castle::where('century_founded', $castle->century_founded)->count() }}</strong>
                </p>
                <p>Принадлежность {{ $castle->affiliation }}: 
                    <strong>{{ App\Models\Castle::where('affiliation', $castle->affiliation)->count() }}</strong>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для увеличения изображения -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $castle->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                @if($castle->image_original)
                    <img 
                        src="{{ Storage::url($castle->image_original) }}" 
                        alt="{{ $castle->image_alt ?? $castle->name }}" 
                        class="img-fluid"
                    >
                @else
                    <p class="text-muted">Изображение не загружено</p>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно подтверждения удаления -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="fas fa-exclamation-triangle"></i> Подтверждение удаления
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Вы уверены, что хотите удалить замок <strong>"{{ $castle->name }}"</strong>?</p>
                <p class="text-danger"><small>Это действие нельзя отменить. Все изображения также будут удалены.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <form action="{{ route('castles.destroy', $castle) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Удалить
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection