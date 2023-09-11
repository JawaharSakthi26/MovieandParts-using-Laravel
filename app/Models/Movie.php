<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;
    protected $fillable=[
        'title_id','movie_name'
    ];

    public function title() {
        return $this->belongsTo(Title::class);
    }

    public function parts() {
        return $this->hasMany(Part::class);
    }
}
