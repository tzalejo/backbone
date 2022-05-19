<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    use HasFactory;

    protected $table = 'settlements';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key',
        'name',
        'zone_type',
        'settlement_type_id',
        'zip_code_id',
        'municipality_id'
    ];

    protected $hidden = [
        'id',
    ];


    public function zipCode()
    {
      return $this->belongsTo(ZipCode::class);
    }


    public function settlementType()
    {
      return $this->belongsTo(SettlementType::class);
    }


    public function municipality()
    {
      return $this->belongsTo(Municipality::class);
    }
}
