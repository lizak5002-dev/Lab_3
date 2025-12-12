<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить новый замок</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Добавить новый замок</h1>
        
        <form action="{{ route('castles.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="name" class="form-label">Название замка *</label>
                <input type="text" class="form-control" id="name" name="name" 
                       value="{{ old('name') }}" required>
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="century_founded" class="form-label">Век основания *</label>
                <input type="text" class="form-control" id="century_founded" name="century_founded"
                       value="{{ old('century_founded') }}" placeholder="13 век" required>
                @error('century_founded')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="year_founded" class="form-label">Год основания</label>
                <input type="number" class="form-control" id="year_founded" name="year_founded"
                       value="{{ old('year_founded') }}" placeholder="1342" min="1000" max="{{ date('Y') }}">
                @error('year_founded')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="location" class="form-label">Месторасположение *</label>
                <input type="text" class="form-control" id="location" name="location"
                       value="{{ old('location') }}" required>
                @error('location')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="affiliation" class="form-label">Принадлежность *</label>
                <input type="text" class="form-control" id="affiliation" name="affiliation"
                       value="{{ old('affiliation') }}" required>
                @error('affiliation')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="owner" class="form-label">Владелец *</label>
                <input type="text" class="form-control" id="owner" name="owner"
                       value="{{ old('owner') }}" required>
                @error('owner')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Описание</label>
                <textarea class="form-control" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="image_filename" class="form-label">Имя файла изображения</label>
                <input type="text" class="form-control" id="image_filename" name="image_filename"
                       value="{{ old('image_filename') }}" placeholder="castle.jpg">
                @error('image_filename')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="btn btn-primary">Создать</button>
            <a href="{{ route('castles.index') }}" class="btn btn-secondary">Отмена</a>
        </form>
    </div>
</body>
</html>