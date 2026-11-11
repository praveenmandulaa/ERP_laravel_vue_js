<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SupplierInvoice extends Model {protected $guarded=[]; protected $casts=[];
public function lines(){return $this->hasMany(SupplierInvoiceItem::class);}
}