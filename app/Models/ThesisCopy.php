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

    // Fixed: Added proper foreign key
    protected $primaryKey = 'id';
    public function thesis()
    {
        return $this->belongsTo(Thesis::class, 'thesis_id');
    }

    // Fixed: Correct polymorphic relationship
    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'borrowable');
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

    // Accessors
    public function getItemTitleAttribute()
    {
        return $this->thesis ? $this->thesis->title : 'Unknown Thesis';
    }

    public function getItemTypeAttribute()
    {
        return 'Thesis';
    }
}