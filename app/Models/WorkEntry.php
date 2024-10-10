<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'start_time',
        'end_time',
        'duration',
        'comments',
        'summary',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'comments' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function  project()
    {
        return $this->belongsTo(Project::class);
    }
}
