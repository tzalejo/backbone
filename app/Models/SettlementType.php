<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SettlementType extends Model
{
    use HasFactory;

    protected $table = 'settlement_types';
    public $timestamps = false;
    protected $fillable = [
        'name',
    ];
    protected $hidden = [
        'id',
    ];


    public function settlements(): HasMany
    {
      return $this->hasMany(Settlement::class);
    }
}
