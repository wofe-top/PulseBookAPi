<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Traits\Filterable;

class Appointment extends Model
{
    use HasFactory, Filterable;


    protected $fillable = [
        'patient_id',
        'doctor_profile_id',
        'appointment_date',
        'start_time',
        'end_time',
        'status',
        'payment_status',
        'notes',
    ];

    protected $casts = ['appointment_date' => 'date'];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
    public function doctorProfile()
    {
        return $this->belongsTo(DoctorProfile::class, 'doctor_profile_id');
    }
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
