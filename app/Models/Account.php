<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // protected $fillable = ['name', 'type', 'description'];

    public function category() {
        return $this->belongsTo(Category::class);
    }
}
