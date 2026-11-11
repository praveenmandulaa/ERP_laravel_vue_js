<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Item extends Model {protected $guarded=[]; protected $casts=['qc_required'=>'boolean'];
public function qcParameters(){return $this->hasMany(ItemQcParameter::class);}
public function samplingRules(){return $this->hasMany(ItemQcSamplingRule::class);}
}