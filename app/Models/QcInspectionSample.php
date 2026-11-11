<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class QcInspectionSample extends Model {protected $guarded=[]; protected $casts=[];
public function results(){return $this->hasMany(QcInspectionResult::class);}
}