<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Municipality extends Model
{
    use HasFactory;

    protected $table = 'municipalities';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'federal_entity_id'
    ];

    protected $hidden = [
        'id',
        'state_id'
    ];

    public function settlements(): HasMany
    {
        return $this->hasMany(Settlement::class);
    }

    public function federalEntity(): BelongsTo
    {
        return $this->belongsTo(FederalEntity::class);
    }

    public function getNameAttribute(string $value): string
    {
        return strtoupper($value);
    }

}
