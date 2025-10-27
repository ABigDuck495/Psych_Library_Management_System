<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ThesisCopy extends Model
{
    use HasFactory;

    protected $fillable = [
        'thesis_id',
        'is_available'
    ];

    // Relationships
    public function thesis()
{
    return $this->belongsTo(Thesis::class, 'thesis_id');
}


    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'copy_id');
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    // Helpers
    public function isAvailable()
    {
        return $this->is_available;
    }

    public function markAsUnavailable()
    {
        $this->update(['is_available' => false]);
    }

    public function markAsAvailable()
    {
        $this->update(['is_available' => true]);
    }
}