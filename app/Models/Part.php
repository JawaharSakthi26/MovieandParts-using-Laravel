<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    use HasFactory;
    protected $fillable=[
        'movie_id','part_name'
    ];

    public function movie() {
        return $this->belongsTo(Movie::class);
    }
}
