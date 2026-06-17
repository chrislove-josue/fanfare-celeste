<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    protected $table = 'documents';
    
    protected $fillable = [
        'title', 'category', 'description', 'file_path', 'file_name',
        'file_size', 'file_type', 'uploaded_by', 'document_date', 'is_public'
    ];

    protected $casts = [
        'document_date' => 'date',
        'is_public' => 'boolean'
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getFileUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif ($bytes == 1) {
            return '1 byte';
        } else {
            return '0 bytes';
        }
    }

    public static function getCategories()
    {
        return [
            'statutes' => 'Statuts et règlements',
            'minutes' => 'Procès-verbaux',
            'financial_report' => 'Rapports financiers',
            'activity_program' => 'Programmes d\'activités',
            'internal' => 'Documents internes',
            'contract' => 'Contrats',
            'certificate' => 'Certificats'
        ];
    }
}