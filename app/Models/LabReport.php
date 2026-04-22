<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LabReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'report_name',
        'file_path',
        'report_date',
    ];

    // file_url accessor
    public function getFileUrlAttribute()
    {
        if (! $this->file_path) {
            return null;
        }
        // use protected download route so Laravel can authorize
        return url('api/v1/storage/' . $this->file_path);
    }

    protected $casts = [
        'report_date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
