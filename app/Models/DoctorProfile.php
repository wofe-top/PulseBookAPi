<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\Filterable;

class DoctorProfile extends Model
{
    use HasFactory, Filterable;
    protected $fillable = [
        'user_id',
        'bio',
        'experience_years',
        'consultation_fee',
    ];


    protected $casts = [
        'bio' => 'array',
    ];

    protected function translatedBio(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->bio[app()->getLocale()] ?? $this->bio['en'] ?? '',
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function specialties(): BelongsToMany
    {
        return $this->belongsToMany(Specialty::class, 'doctor_specialty');
    }

    public function doctorSchedual()
    {
        return $this->hasOne(DoctorSchedule::class, 'doctor_profile_id');
    }
}
