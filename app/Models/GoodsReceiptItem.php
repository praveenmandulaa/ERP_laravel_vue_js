<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class GoodsReceiptItem extends Model {protected $guarded=[]; protected $casts=[];
public function lots(){return $this->hasMany(GoodsReceiptLot::class);}
}