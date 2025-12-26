@extends('layouts.app')

@section('title', isset($castle) ? "Редактирование: {$castle->name}" : 'Добавление нового замка')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas {{ isset($castle) ? 'fa-edit text-warning' : 'fa-plus text-success' }}"></i>
                    {{ isset($castle) ? "Редактирование замка" : 'Добавление нового замка' }}
                    
                    @if(isset($castle))
                        <small class="text-muted">"{{ $castle->name }}"</small>
                    @endif
                </h4>
            </div>
            
            <div class="card-body">
                <form 
                    action="{{ isset($castle) ? route('castles.update', $castle) : route('castles.store') }}" 
                    method="POST" 
                    enctype="multipart/form-data"
                    novalidate
                >
                    @csrf
                    @if(isset($castle))
                        @method('PUT')
                    @endif

                    <!-- Название -->
                    <div class="mb-3">
                        <label for="name" class="form-label required">
                            <i class="fas fa-castle"></i> Название замка
                        </label>
                        <input 
                            type="text" 
                            class="form-control @error('name') is-invalid @enderror" 
                            id="name" 
                            name="name" 
                            value="{{ old('name', $castle->name ?? '') }}"
                            required
                            minlength="3"
                            maxlength="255"
                        >
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Век основания -->
                    <div class="mb-3">
                        <label for="century_founded" class="form-label required">
                            <i class="fas fa-calendar-alt"></i> Век основания
                        </label>
                        <select 
                            class="form-select @error('century_founded') is-invalid @enderror" 
                            id="century_founded" 
                            name="century_founded" 
                            required
                        >
                            <option value="">Выберите век...</option>
                            @for($i = 13; $i <= 18; $i++)
                                <option value="{{ $i }} век" 
                                        {{ old('century_founded', $castle->century_founded ?? '') == "$i век" ? 'selected' : '' }}>
                                    {{ $i }} век
                                </option>
                            @endfor
                        </select>
                        @error('century_founded')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Год основания -->
                    <div class="mb-3">
                        <label for="year_founded" class="form-label">
                            <i class="fas fa-calendar"></i> Год основания
                        </label>
                        <input 
                            type="number" 
                            class="form-control @error('year_founded') is-invalid @enderror" 
                            id="year_founded" 
                            name="year_founded" 
                            value="{{ old('year_founded', $castle->year_founded ?? '') }}"
                            min="1000"
                            max="{{ date('Y') }}"
                            placeholder="Например: 1342"
                        >
                        @error('year_founded')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Месторасположение -->
                    <div class="mb-3">
                        <label for="location" class="form-label required">
                            <i class="fas fa-map-marker-alt"></i> Месторасположение
                        </label>
                        <input 
                            type="text" 
                            class="form-control @error('location') is-invalid @enderror" 
                            id="location" 
                            name="location" 
                            value="{{ old('location', $castle->location ?? '') }}"
                            required
                            minlength="3"
                            maxlength="255"
                        >
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Принадлежность -->
                    <div class="mb-3">
                        <label for="affiliation" class="form-label required">
                            <i class="fas fa-flag"></i> Принадлежность
                        </label>
                        <input 
                            type="text" 
                            class="form-control @error('affiliation') is-invalid @enderror" 
                            id="affiliation" 
                            name="affiliation" 
                            value="{{ old('affiliation', $castle->affiliation ?? '') }}"
                            required
                            maxlength="255"
                        >
                        <div class="form-text">
                            Например: Ливонский орден, Дерптское епископство и т.д.
                        </div>
                        @error('affiliation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Владелец -->
                    <div class="mb-3">
                        <label for="owner" class="form-label required">
                            <i class="fas fa-crown"></i> Владелец замка
                        </label>
                        <input 
                            type="text" 
                            class="form-control @error('owner') is-invalid @enderror" 
                            id="owner" 
                            name="owner" 
                            value="{{ old('owner', $castle->owner ?? '') }}"
                            required
                            minlength="3"
                            maxlength="255"
                        >
                        @error('owner')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Описание -->
                    <div class="mb-3">
                        <label for="description" class="form-label required">
                            <i class="fas fa-file-alt"></i> Описание
                        </label>
                        <textarea 
                            class="form-control @error('description') is-invalid @enderror" 
                            id="description" 
                            name="description" 
                            rows="5"
                            required
                            minlength="10"
                        >{{ old('description', $castle->description ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Загрузка изображения -->
                    <div class="mb-4">
                        <label for="image" class="form-label">
                            <i class="fas fa-image"></i> Изображение замка
                        </label>
                        
                        @if(isset($castle) && $castle->image_thumbnail)
                            <div class="mb-3">
                                <p class="text-muted">
                                    <i class="fas fa-info-circle"></i> 
                                    Текущее изображение (для замены загрузите новое):
                                </p>
                                <div class="text-center">
                                    <img 
                                        src="{{ Storage::url($castle->image_thumbnail) }}" 
                                        alt="{{ $castle->image_alt ?? $castle->name }}" 
                                        class="img-thumbnail mb-2"
                                        style="max-width: 300px;"
                                    >
                                    <div class="form-text small">
                                        Файл: {{ basename($castle->image_thumbnail) }}
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <input 
                            type="file" 
                            class="form-control @error('image') is-invalid @enderror" 
                            id="image" 
                            name="image"
                            accept="image/jpeg,image/png,image/jpg,image/gif"
                            {{ isset($castle) ? '' : '' }} <!-- Не required при редактировании -->
                        >
                        <div class="form-text">
                            @if(isset($castle))
                                Оставьте пустым, чтобы сохранить текущее изображение
                            @else
                                Загрузите изображение замка
                            @endif
                            <br>Поддерживаемые форматы: JPG, PNG, GIF. Максимальный размер: 5MB.
                        </div>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Alt текст для изображения -->
                    <div class="mb-3">
                        <label for="image_alt" class="form-label">
                            <i class="fas fa-text-height"></i> Описание изображения (alt текст)
                        </label>
                        <input 
                            type="text" 
                            class="form-control @error('image_alt') is-invalid @enderror" 
                            id="image_alt" 
                            name="image_alt" 
                            value="{{ old('image_alt', $castle->image_alt ?? '') }}"
                            maxlength="255"
                            placeholder="Например: Замок Вастселийна, вид с юго-восточной стороны"
                        >
                        @error('image_alt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Кнопки -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Назад
                        </a>
                        
                        <div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                {{ isset($castle) ? 'Сохранить изменения' : 'Создать замок' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection