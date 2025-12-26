<?php

namespace App\Http\Controllers;

use App\Models\Castle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;


class CastleController extends Controller
{
    
    //Правила валидации
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



    //Обработка загрузки изображения (без ресайза)
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


    public function index(Request $request, \App\Models\User $User = null)
    {
        if (is_null($User)) {
            // По умолчанию SoftDeletes скроет удаленные для всех
            $castles = Castle::all();
            
            // Но админу покажем все
            if (Auth::check() && Auth::user()->is_admin) {
                $castles = Castle::withTrashed()->get();
            }
        } else {
            // Замки конкретного пользователя (только неудаленные)
            $castles = $User->castles; // Автоматически скрывает удаленные
        }
        
        return view('castles.index', compact(['castles']));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::check()) {
            abort(401, "Authentication required");
        }
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
        // Проверка: это мой замок ИЛИ я админ
        if (!Gate::allows('modify-object', $castle)) {
            abort(403, "Вы можете редактировать только свои замки");
        }
        
        return view('castles.form', compact('castle'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, Castle $castle)
    {
        if (!Gate::allows('modify-object', $castle)) {
            abort(403, "Вы можете редактировать только свои замки");
        }
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

    public function destroy(Castle $castle)
    {
        if (!Gate::allows('modify-object', $castle)) {
            abort(403, "Вы можете редактировать только свои замки");
        }
        // Удаляем запись
        $castle->delete();
        
        // Редирект с сообщением
        return redirect()->route('castles.index')
            ->with('success', 'Замок успешно удален!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function restore(Request $request, int $id)
    {
        // Восстановить может админ ИЛИ владелец замка
        $castle = Castle::withTrashed()->findOrFail($id);
        
        if (!Auth::check() || (Auth::id() != $castle->user_id && !Auth::user()->is_admin)) {
            abort(403, "Unauthorized");
        }
        
        $castle->restore();
        
        return redirect($request['return_url'] ?? route('castles.index'))
            ->with('success', 'Замок восстановлен!');
    }
    // удалить насовсем
    public function purge(Request $request, int $id)
    {
        // Удалить окончательно может ТОЛЬКО админ
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'Только администратор может удалять навсегда');
        }
        
        $castle = Castle::withTrashed()->findOrFail($id);
        $castle->forceDelete();
        
        return redirect($request['return_url'] ?? route('castles.index'))
            ->with('success', 'Замок удален навсегда!');
    }
}


