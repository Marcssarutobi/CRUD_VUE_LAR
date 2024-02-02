<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'name',
        'categorie_id',
        'qte',
        'Pachats',
        'Pvente'
    ];

    public function categorie(){
        return $this->belongsTo(Categorie::class);
    }
}
