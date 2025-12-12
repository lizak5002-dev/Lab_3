<?php

namespace App\Http\Controllers;

use App\Models\Castle;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CastleController extends Controller
{
    /**
     * Правила валидации
     */
    protected function validationRules($castle = null)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'century_founded' => 'required|string|max:50',
            'year_founded' => 'nullable|integer|min:1000|max:' . date('Y'),
            'location' => 'required|string|max:255',
            'affiliation' => 'required|string|max:255',
            'owner' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'image_alt' => 'nullable|string|max:255',
        ];
        
        // Уникальность имени только при создании или если имя изменилось
        if ($castle) {
            $rules['name'] .= '|unique:castles,name,' . $castle->id;
        } else {
            $rules['name'] .= '|unique:castles,name';
        }
        
        return $rules;
    }


/**
 * Обработка загрузки изображения (без ресайза)
 */
    protected function handleImageUpload(Request $request, $castle = null)
    {
        if (!$request->hasFile('image')) {
            return null;
        }

        $image = $request->file('image');
        $filename = Str::slug($request->name) . '-' . time() . '.' . 
                    strtolower($image->getClientOriginalExtension());
        
        // Папка для хранения
        $path = 'img';
        
        // Удаляем старые изображения если редактируем
        if ($castle) {
            if ($castle->image_original && $castle->image_original !== 'img/default-castle.jpg') {
                Storage::disk('public')->delete($castle->image_original);
            }
            // Не удаляем thumbnail и preview если они такие же как original
        }
        
        // Сохраняем новое изображение
        $imagePath = $image->storeAs($path, $filename, 'public');
        
        return [
            'original' => $imagePath,
            'thumbnail' => $imagePath,
            'preview' => $imagePath,
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Получаем уникальные века из БД
        $availableCenturies = Castle::select('century_founded')
            ->distinct()
            ->orderBy('century_founded')
            ->pluck('century_founded');
            
        // Получаем уникальные принадлежности из БД
        $availableAffiliations = Castle::select('affiliation')
            ->distinct()
            ->orderBy('affiliation')
            ->pluck('affiliation');
            
        // Фильтрация
        $query = Castle::query();
            
        // Фильтр по веку
        if ($request->has('century') && $request->century) {
            // Если передано число, ищем как "13%"
            if (is_numeric($request->century)) {
                $query->where('century_founded', 'like', $request->century . '%');
            } else {
                $query->where('century_founded', $request->century);
            }
        }
            
        // Фильтр по принадлежности
        if ($request->has('affiliation') && $request->affiliation) {
            $query->where('affiliation', $request->affiliation);
        }
            
        // Сортировка и пагинация
        //$castles = Castle::orderBy('id', 'asc')->get();
        $castles = $query->orderBy('created_at', 'desc')->paginate(12);
            
        // Возвращаем все необходимые переменные
        return view('castles.index', compact(
            'castles', 
            'availableCenturies', 
            'availableAffiliations'
        ));
    }
    /**
     * Show the form for creating a new resource.
     */
        public function create()
    {
        // Возвращаем форму создания
        return view('castles.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Валидация
        $validated = $request->validate([
                'name' => 'required|string|max:255',
                'century_founded' => 'required|string|max:50',
                'year_founded' => 'nullable|integer|min:1000|max:' . date('Y'),
                'location' => 'required|string|max:255',
                'affiliation' => 'required|string|max:255',
                'owner' => 'required|string|max:255',
                'description' => 'required|string|min:10',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'image_alt' => 'nullable|string|max:255',
        ]);
            
            // Обработка изображения
        $imagePaths = $this->handleImageUpload($request);
            
            // Создаем slug
        $validated['slug'] = Str::slug($validated['name']);
            
            // Проверяем уникальность slug
        $counter = 1;
        $originalSlug = $validated['slug'];
        while (Castle::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }
            
            // Добавляем пути к изображениям
        if ($imagePaths) {
            $validated['image_original'] = $imagePaths['original'];
            $validated['image_thumbnail'] = $imagePaths['thumbnail'];
            $validated['image_preview'] = $imagePaths['preview'];
            $validated['image_filename'] = basename($imagePaths['original']);
        } else {
                // Если изображение не загружено, ставим дефолтные значения
            $validated['image_filename'] = 'default-castle.jpg';
            $validated['image_thumbnail'] = 'img/default-castle.jpg';
            $validated['image_original'] = 'img/default-castle.jpg';
            $validated['image_preview'] = 'img/default-castle.jpg';
        }
            
            // Создаем запись
        $castle = Castle::create($validated);
            
        return redirect()->route('castles.show', $castle)
            ->with('success', 'Замок успешно создан!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Castle $castle)
    {
            // Автоматическое разрешение модели через Route Model Binding
        return view('castles.show', compact('castle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Castle $castle)
    {
        return view('castles.form', compact('castle'));
    }

    /**
     * Update the specified resource in storage.
     */
    /**
 * Update the specified resource in storage.
 */
    public function update(Request $request, Castle $castle)
    {
        // Валидация
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:castles,name,' . $castle->id,
            'century_founded' => 'required|string|max:50',
            'year_founded' => 'nullable|integer|min:1000|max:' . date('Y'),
            'location' => 'required|string|max:255',
            'affiliation' => 'required|string|max:255',
            'owner' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'image_alt' => 'nullable|string|max:255',
        ]);
        
        // Обработка нового изображения (если загружено)
        $imagePaths = $this->handleImageUpload($request, $castle);
        
        // Обновляем slug если изменилось название
        if ($castle->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
            
            // Проверяем уникальность
            $counter = 1;
            $originalSlug = $validated['slug'];
            while (Castle::where('slug', $validated['slug'])
                    ->where('id', '!=', $castle->id)
                    ->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }
        
        // Обновляем пути к изображениям если загружено новое
        if ($imagePaths) {
            $validated['image_original'] = $imagePaths['original'];
            $validated['image_thumbnail'] = $imagePaths['thumbnail'];
            $validated['image_preview'] = $imagePaths['preview'];
            $validated['image_filename'] = basename($imagePaths['original']);
        }
        // Если изображение не загружено, сохраняем старые пути
        else {
            $validated['image_original'] = $castle->image_original;
            $validated['image_thumbnail'] = $castle->image_thumbnail;
            $validated['image_preview'] = $castle->image_preview;
            $validated['image_filename'] = $castle->image_filename;
        }
        
        // Обновляем запись
        $castle->update($validated);
        
        return redirect()->route('castles.show', $castle)
            ->with('success', 'Замок успешно обновлен!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Castle $castle)
    {
        // Удаляем запись
        $castle->delete();
        
        // Редирект с сообщением
        return redirect()->route('castles.index')
            ->with('success', 'Замок успешно удален!');
    }
}
