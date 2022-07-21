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
        'id',
        'name',
        'code'
    ];
    protected $maps = [
        'id' => 'key'
    ];

    protected $hidden = [
        'id',
    ];

    protected $appends = ['key'];

    public function getNameAttribute(string $value): string
    {
        return strtoupper($value);
    }

    public function getKeyAttribute()
    {
        return $this->attributes['id'];
    }
}
