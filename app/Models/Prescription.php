<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'medication',
        'dosage',
        'instructions',
        'file_path',
    ];

    // url for pdf
    public function getFileUrlAttribute()
    {
        if (! $this->file_path) {
            return null;
        }
        return url('api/v1/storage/' . $this->file_path);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
