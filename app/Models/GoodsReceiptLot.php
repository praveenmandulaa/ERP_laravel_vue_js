<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class GoodsReceiptLot extends Model {protected $guarded=[]; protected $casts=[];
public function inspections(){return $this->hasMany(QcInspection::class);}
}