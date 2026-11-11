<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
public function up():void {
 Schema::table('users',function(Blueprint $t){$t->string('role')->default('buyer');});
 Schema::create('suppliers',function(Blueprint $t){$t->id();$t->string('name');$t->string('gst_number')->nullable();$t->string('email')->nullable();$t->timestamps();});
 Schema::create('warehouses',function(Blueprint $t){$t->id();$t->string('name');$t->string('code')->unique();$t->timestamps();});
 Schema::create('items',function(Blueprint $t){$t->id();$t->string('item_code')->unique();$t->string('name');$t->string('category');$t->string('uom');$t->boolean('qc_required')->default(true);$t->unsignedInteger('min_lot_size')->default(1);$t->unsignedInteger('max_lot_size')->nullable();$t->string('lot_size_uom')->nullable();$t->timestamps();});
 Schema::create('item_qc_parameters',function(Blueprint $t){$t->id();$t->foreignId('item_id')->constrained()->cascadeOnDelete();$t->string('parameter_name');$t->decimal('min_value',12,3);$t->decimal('max_value',12,3);$t->string('unit');$t->boolean('is_required')->default(true);$t->unique(['item_id','parameter_name']);$t->timestamps();});
 Schema::create('item_qc_sampling_rules',function(Blueprint $t){$t->id();$t->foreignId('item_id')->constrained()->cascadeOnDelete();$t->unsignedInteger('min_quantity');$t->unsignedInteger('max_quantity')->nullable();$t->unsignedInteger('sample_count');$t->timestamps();});
 Schema::create('purchase_orders',function(Blueprint $t){$t->id();$t->string('po_number')->unique();$t->foreignId('supplier_id')->constrained();$t->foreignId('warehouse_id')->constrained();$t->foreignId('created_by')->constrained('users');$t->foreignId('approved_by')->nullable()->constrained('users');$t->date('order_date');$t->date('expected_date')->nullable();$t->string('status')->default('draft');$t->decimal('total',16,2)->default(0);$t->timestamps();});
 Schema::create('purchase_order_items',function(Blueprint $t){$t->id();$t->foreignId('purchase_order_id')->constrained()->cascadeOnDelete();$t->foreignId('item_id')->constrained();$t->unsignedInteger('quantity');$t->string('uom');$t->decimal('unit_price',14,2);$t->decimal('tax_rate',6,2)->default(0);$t->decimal('line_total',16,2);$t->timestamps();});
 Schema::create('goods_receipts',function(Blueprint $t){$t->id();$t->string('grn_number')->unique();$t->foreignId('purchase_order_id')->constrained();$t->foreignId('created_by')->constrained('users');$t->date('received_date');$t->timestamps();});
 Schema::create('goods_receipt_items',function(Blueprint $t){$t->id();$t->foreignId('goods_receipt_id')->constrained()->cascadeOnDelete();$t->foreignId('purchase_order_item_id')->constrained();$t->unsignedInteger('quantity');$t->timestamps();});
 Schema::create('goods_receipt_lots',function(Blueprint $t){$t->id();$t->foreignId('goods_receipt_item_id')->constrained()->cascadeOnDelete();$t->string('lot_number');$t->unsignedInteger('quantity');$t->date('manufactured_at')->nullable();$t->date('expires_at')->nullable();$t->string('qc_status')->default('hold');$t->timestamps();$t->unique(['goods_receipt_item_id','lot_number']);});
 Schema::create('qc_inspections',function(Blueprint $t){$t->id();$t->foreignId('goods_receipt_lot_id')->constrained();$t->foreignId('inspector_id')->constrained('users');$t->foreignId('approver_id')->nullable()->constrained('users');$t->unsignedInteger('required_samples');$t->json('parameters_snapshot');$t->json('sampling_rule_snapshot')->nullable();$t->string('status')->default('draft');$t->text('reason')->nullable();$t->timestamp('submitted_at')->nullable();$t->timestamp('approved_at')->nullable();$t->timestamps();});
 Schema::create('qc_inspection_samples',function(Blueprint $t){$t->id();$t->foreignId('qc_inspection_id')->constrained()->cascadeOnDelete();$t->unsignedInteger('sample_number');$t->unique(['qc_inspection_id','sample_number']);$t->timestamps();});
 Schema::create('qc_inspection_results', function (Blueprint $t) {
    $t->id();

    $t->foreignId('qc_inspection_sample_id')
        ->constrained()
        ->cascadeOnDelete();

    $t->string('parameter_name');
    $t->decimal('measured_value', 12, 3);
    $t->boolean('passed');

    // Short unique constraint name to avoid MySQL error 1059
    $t->unique(
        ['qc_inspection_sample_id', 'parameter_name'],
        'qc_result_sample_param_unique'
    );

    $t->timestamps();
});
 Schema::create('stock_movements',function(Blueprint $t){$t->id();$t->foreignId('goods_receipt_lot_id')->constrained();$t->foreignId('item_id')->constrained();$t->foreignId('warehouse_id')->constrained();$t->string('from_bucket')->nullable();$t->string('to_bucket');$t->unsignedInteger('quantity');$t->string('reference')->unique();$t->timestamps();});
 Schema::create('supplier_invoices',function(Blueprint $t){$t->id();$t->string('invoice_number');$t->foreignId('supplier_id')->constrained();$t->foreignId('purchase_order_id')->constrained();$t->foreignId('created_by')->constrained('users');$t->date('invoice_date');$t->string('status')->default('draft');$t->decimal('total',16,2);$t->unique(['supplier_id','invoice_number']);$t->timestamps();});
 Schema::create('supplier_invoice_items',function(Blueprint $t){$t->id();$t->foreignId('supplier_invoice_id')->constrained()->cascadeOnDelete();$t->foreignId('goods_receipt_lot_id')->constrained();$t->unsignedInteger('quantity');$t->decimal('unit_price',14,2);$t->decimal('tax_rate',6,2);$t->decimal('line_total',16,2);$t->timestamps();});
 Schema::create('audit_logs',function(Blueprint $t){$t->id();$t->foreignId('user_id')->nullable()->constrained('users');$t->string('action');$t->string('entity');$t->unsignedBigInteger('entity_id');$t->json('details')->nullable();$t->timestamps();});
}
public function down():void {foreach(['audit_logs','supplier_invoice_items','supplier_invoices','stock_movements','qc_inspection_results','qc_inspection_samples','qc_inspections','goods_receipt_lots','goods_receipt_items','goods_receipts','purchase_order_items','purchase_orders','item_qc_sampling_rules','item_qc_parameters','items','warehouses','suppliers'] as $table) Schema::dropIfExists($table);Schema::table('users',fn(Blueprint $t)=>$t->dropColumn('role'));}
};