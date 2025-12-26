<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Castle extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * Поля, которые можно массово назначать
     */
    protected $fillable = [
        'name',
        'slug',
        'century_founded',
        'year_founded',
        'location',
        'affiliation',
        'owner',
        'description',
        'image_filename',
        'image_original',     
        'image_thumbnail',    
        'image_preview',     
        'image_alt', 
        'user_id',
    ];

    /**
     * Поля, которые должны быть приведены к определенному типу
     */
    protected $casts = [
        'year_founded' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * МУТАТОР для поля century_founded
     * Автоматически форматируем при сохранении
     */
    protected function centuryFounded(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value,
            set: fn ($value) => str_contains($value, 'век') ? $value : $value . ' век',
        );
    }

    /**
     * МУТАТОР для поля year_founded
     * Преобразуем в целое число и валидируем
     */
    protected function yearFounded(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value,
            set: function ($value) {
                if (is_null($value)) {
                    return null;
                }
                
                // Если передана строка типа "14 (1342 год)"
                if (preg_match('/\b(\d{4})\b/', $value, $matches)) {
                    return (int) $matches[1];
                }
                
                return (int) $value;
            },
        );
    }

    /**
     * МУТАТОР для поля created_at (пример с датой)
     * Форматируем дату создания при получении
     */
    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('d.m.Y H:i'),
        );
    }

    /**
     * МУТАТОР для поля updated_at
     * Показываем "только что", "5 минут назад" и т.д.
     */
    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->diffForHumans(),
        );
    }

    /**
     * ВИРТУАЛЬНОЕ ПОЛЕ (акцессор)
     * Полное название с веком
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: function () {
                $year = $this->year_founded 
                    ? " ({$this->year_founded} год)"
                    : '';
                return $this->name . $year;
            },
        );
    }

    /**
     * ВИРТУАЛЬНОЕ ПОЛЕ (акцессор)
     * Возраст замка (сколько лет назад основан)
     */
    protected function age(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->year_founded) {
                    return 'Неизвестно';
                }
                
                $currentYear = date('Y');
                $age = $currentYear - $this->year_founded;
                
                return $age . ' лет';
            },
        );
    }

    /**
     * СКОП для поиска по веку
     */
    public function scopeCentury($query, $century)
    {
        return $query->where('century_founded', 'like', "%$century%");
    }

    /**
     * СКОП для поиска по принадлежности
     */
    public function scopeAffiliation($query, $affiliation)
    {
        return $query->where('affiliation', $affiliation);
    }

    /**
     * Акцессор для получения пути к изображению
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->image_thumbnail && Storage::disk('public')->exists($this->image_thumbnail)) {
                    return Storage::url($this->image_thumbnail);
                }
                return asset('storage/img/default-castle.jpg');
            },
        );
    }

    /**
     * Акцессор для получения оригинального изображения
     */
    protected function originalImageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->image_original && Storage::disk('public')->exists($this->image_original)) {
                    return Storage::url($this->image_original);
                }
                return null;
            },
        );
    }

    public function user()
    {
    return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::creating(function ($castle) {
            if (!Auth::check())
                abort(401, "Authentication required");
            $castle->user_id = Auth::id();
        });
        static::updating(function ($castle) {
            $user = Auth::user();
            
            if (!$user) {
                abort(401, 'Требуется авторизация');
            }
            
            // Проверка: текущий пользователь - владелец ИЛИ админ
            if ($user->id !== $castle->user_id && !$user->is_admin) {
                abort(403, 'Вы можете редактировать только свои замки');
            }
            
            return true; // Разрешаем обновление
        });

        static::deleting(function ($castle) {
            $user = Auth::user();
            
            if (!$user) {
                abort(401, 'Требуется авторизация');
            }
            
            if ($user->id !== $castle->user_id && !$user->is_admin) {
                abort(403, 'Вы можете удалять только свои замки');
            }
            
            return true; // Разрешаем удаление
        });
       
    }
}