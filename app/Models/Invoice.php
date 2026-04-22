<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'amount',
        'status',
        'payment_date',
        'file_path',
    ];

    public function getFileUrlAttribute()
    {
        if (! $this->file_path) {
            return null;
        }
        return url('api/v1/storage/' . $this->file_path);
    }

    protected $casts = [
        'payment_date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
