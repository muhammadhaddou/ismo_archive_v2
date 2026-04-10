<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'trainee_id',
        'document_type',
        'status',
        'appointment_date',
        'admin_message',
        'is_read_by_admin',
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
        'is_read_by_admin' => 'boolean',
    ];

    public function trainee()
    {
        return $this->belongsTo(Trainee::class);
    }
}
