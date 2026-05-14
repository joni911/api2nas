<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiData extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_id',
        'nama_file',
        'ip_address',
        'file_path',
        'url',
        'id_tabel',
        'tabel_name'
    ];

    protected $casts = [
        'id_tabel' => 'string',
        'tabel_name' => 'string'
    ];

    public function apiKey()
    {
        return $this->belongsTo(ApiKey::class, 'api_id');
    }
}