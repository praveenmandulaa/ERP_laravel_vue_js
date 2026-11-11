<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class QcInspection extends Model {protected $guarded=[]; protected $casts=['parameters_snapshot'=>'array','sampling_rule_snapshot'=>'array'];
public function samples(){return $this->hasMany(QcInspectionSample::class);}
}