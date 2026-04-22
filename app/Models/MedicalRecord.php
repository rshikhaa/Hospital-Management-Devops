<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'diagnosis',
        'treatment',
        'notes',
        'record_date',
        'file_path',
    ];

    protected $casts = [
        'record_date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    // URL accessor so frontend can easily download
    public function getFileUrlAttribute()
    {
        if (! $this->file_path) {
            return null;
        }
        // serve through our protected controller so auth can be verified
        return url('api/v1/storage/' . $this->file_path);
    }
}
