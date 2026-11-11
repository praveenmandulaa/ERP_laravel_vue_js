<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\{User,Item,Supplier,Warehouse};
class DatabaseSeeder extends Seeder {public function run():void {
foreach(['buyer','manager','inspector','qc_manager','accounts'] as $role) User::firstOrCreate(['email'=>$role.'@example.test'],['name'=>ucwords(str_replace('_',' ',$role)),'password'=>'password','role'=>$role]);
Supplier::firstOrCreate(['name'=>'Demo Steel Supplier'],['gst_number'=>'36ABCDE1234F1Z5','email'=>'supplier@example.test']);
Warehouse::firstOrCreate(['code'=>'WH1'],['name'=>'Main Warehouse']);
$item=Item::firstOrCreate(['item_code'=>'ITEM001'],['name'=>'Steel Component','category'=>'Raw Material','uom'=>'PCS','qc_required'=>true,'min_lot_size'=>1,'max_lot_size'=>5000,'lot_size_uom'=>'PCS']);
foreach(['Height'=>'mm','Width'=>'mm','Length'=>'mm','Thickness'=>'mm','Weight'=>'g'] as $p=>$unit)$item->qcParameters()->firstOrCreate(['parameter_name'=>$p],['min_value'=>1,'max_value'=>20,'unit'=>$unit,'is_required'=>true]);
foreach([[1,100,2],[101,500,5],[501,1000,10],[1001,null,15]] as [$min,$max,$count])$item->samplingRules()->firstOrCreate(['min_quantity'=>$min],['max_quantity'=>$max,'sample_count'=>$count]);
}}
