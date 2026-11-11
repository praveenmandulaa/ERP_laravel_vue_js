<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PurchaseOrder extends Model {protected $guarded=[]; protected $casts=[];
public function lines(){return $this->hasMany(PurchaseOrderItem::class);}
}