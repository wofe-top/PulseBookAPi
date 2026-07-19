<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Specialty extends Model
{
    use HasFactory;
    protected $fillabe  = ['name'];
    protected $casts = ['name' => 'array'];

    protected function translatedName(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->name[app()->getLocale()] ?? $this->name['en'] ?? '',
        );
    }

    public function doctors(): BelongsToMany
    {
        return $this->belongsToMany(DoctorProfile::class, 'doctor_specialty');
    }
}
