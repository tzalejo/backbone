<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    use HasFactory;

    protected $table = 'municipalities';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'federal_entity_id'
    ];

    protected $hidden = [
        'id',
        'state_id'
    ];

    public function settlements()
    {
        return $this->hasMany(Settlement::class);
    }

    public function federalEntity()
    {
        return $this->belongsTo(FederalEntity::class);
    }

    /**
     * Get the post title.
     *
     * @param string $value
     * @return string
     */
    public function getNameAttribute($value)
    {
        return strtoupper($value);
    }

}
