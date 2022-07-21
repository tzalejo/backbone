<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FederalEntity extends Model
{
    use HasFactory;

    protected $table = 'federal_entities';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'code'
    ];
    protected $hidden = [
        'id'
    ];

    public function getNameAttribute(string $value): string
    {
        return strtoupper($value);
    }

}
