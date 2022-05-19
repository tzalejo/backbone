<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZipCode extends Model
{
    use HasFactory;
    protected $table = 'zip_codes';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'zip_code',
    ];

    protected $hidden = [
        'id',
    ];


    public function settlements()
    {
      return $this->hasMany(Settlement::class);
    }

    public function scopeFindByZipCode(Builder $query, string $zip_code): Builder
    {
      return $query->where('zip_code', $zip_code);
    }

}