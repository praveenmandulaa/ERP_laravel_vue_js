<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ErpController as E;
use App\Models\{PurchaseOrder,GoodsReceiptLot,QcInspection};
Route::get('/',fn()=>redirect('/erp'));
Route::get('/erp',fn()=>view('erp'));
Route::prefix('api')->group(function(){
 Route::post('/login',[E::class,'login'])->middleware('guest');
 Route::middleware('auth')->group(function(){
 Route::post('/logout',[E::class,'logout']);Route::get('/me',[E::class,'me']);Route::get('/overview',[E::class,'overview']);
 Route::post('/items',[E::class,'item']);Route::get('/items/{item}/qc-samples',[E::class,'samples']);
 Route::post('/suppliers',[E::class,'supplier']);Route::post('/warehouses',[E::class,'warehouse']);
 Route::post('/purchase-orders',[E::class,'po']);Route::post('/purchase-orders/{po}/submit',fn(PurchaseOrder $po)=>(app(E::class))->poAction($po,'submit'));
 Route::post('/purchase-orders/{po}/approve',fn(PurchaseOrder $po)=>(app(E::class))->poAction($po,'approve'));
 Route::post('/goods-receipts',[E::class,'grn']);Route::post('/lots/{lot}/inspect',[E::class,'startInspection']);
 Route::post('/qc-inspections/{inspection}/measurements',[E::class,'saveMeasurements']);Route::post('/qc-inspections/{inspection}/submit',[E::class,'submitInspection']);Route::post('/qc-inspections/{inspection}/approve',[E::class,'approveInspection']);
 Route::post('/supplier-invoices',[E::class,'invoice']);
 });
});
