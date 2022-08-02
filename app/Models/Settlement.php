<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Settlement extends Model
{
    use HasFactory;

    protected $table = 'settlements';
    public $timestamps = false;
    protected $fillable = [
        'key',
        'name',
        'zone_type',
        'zip_code_id',
        'municipality_id',
        'municipality',
        'settlement_type_id'
    ];
    protected $hidden = [
        'id',
        'settlement_type_id',
        'zip_code_id',
        'municipality_id',
        'municipality',
    ];

    public function zipCode(): BelongsTo
    {
        return $this->belongsTo(ZipCode::class);
    }

    public function settlementType(): BelongsTo
    {
        return $this->belongsTo(SettlementType::class);
    }

    public function municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class);
    }

    public function getNameAttribute(string $value): string
    {
        return strtoupper($value);
    }

    public function getZoneTypeAttribute(string $value): string
    {
        return strtoupper($value);
    }
}
