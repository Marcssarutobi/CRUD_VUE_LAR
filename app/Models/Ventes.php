<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ventes extends Model
{
    use HasFactory;

    protected $fillable = ['ProductId', 'QteAchat', 'PTotal'];
    public function produit()
    {
        return $this->belongsTo(Produit::class, 'ProductId');
    }
}
