<?php

// app/Models/DepositItem.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositItem extends Model
{
    use HasFactory;

    protected $fillable = ['deposit_id', 'category_id', 'weight', 'subtotal'];

    public function deposit()
    {
        return $this->belongsTo(Deposit::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
