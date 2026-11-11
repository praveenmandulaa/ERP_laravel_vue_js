<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class GoodsReceipt extends Model {protected $guarded=[]; protected $casts=[];
public function lines(){return $this->hasMany(GoodsReceiptItem::class);}
}