<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThesisCopy extends Model
{
    protected $fillable = [
        'thesis_id',
        'is_available',
    ];
    //relations rawr freaky
    public function thesis()
    {
        return $this->belongsTo(Thesis::class, 'thesis_id');
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'copy_id');
    }
    public function isAvailable(): bool
    {
        return $this->is_available;
    }

}
