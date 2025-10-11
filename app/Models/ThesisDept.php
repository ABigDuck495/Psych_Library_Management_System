<?php

namespace App\Models;

use App\Models\Thesis;
use Illuminate\Database\Eloquent\Model;
use illuminate\Database\Eloquent\Factories\HasFactory;

class ThesisDept extends Model
{
    use Hasfactory;

    protected $fillable = ['dept_name'];

    public function thesis()
    {
        return $this->hasMany(Thesis::class, 'dept_id');
    }
    public function getThesisCountAttribute(): int
    {
        return $this->thesis()->count();
    }
    public function scopeWithThesis($query)
    {
        return $query->whereHas('thesis');
    }
}
