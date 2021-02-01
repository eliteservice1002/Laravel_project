<?php

use App\Domains\Accounting\Models\AccountingAccount;
use App\Domains\Checkout\Models\Cart;
use App\Domains\Core\Models\Permission;
use App\Domains\Core\Models\Role;
use App\Domains\Customers\Models\Customer;
use App\Domains\Finance\Models\Currency;
use App\Domains\Finance\Models\ExchangeRate;
use App\Domains\Finance\Models\Tax;
use App\Domains\Finance\Models\TaxRate;
use App\Domains\Inventory\Models\InventoryArea;
use App\Domains\Inventory\Models\InventoryLocation;
use App\Domains\Inventory\Models\InventoryMovement;
use App\Domains\Inventory\Models\TransferOrder;
use App\Domains\Inventory\Models\TransferOrderLineItem;
use App\Domains\Manufacturing\Models\WorkOrder;
use App\Domains\Manufacturing\Models\WorkOrderLineItem;
use App\Domains\Marketing\Models\ContentPage;
use App\Domains\Marketing\Models\ContentPageBlock;
use App\Domains\Marketing\Models\ContentPageBlockItem;
use App\Domains\Marketing\Models\Slug;
use App\Domains\OrderFulfillment\Models\Order;
use App\Domains\ProductCatalog\Models\Product;
use App\Domains\ProductCatalog\Models\ProductAttribute;
use App\Domains\ProductCatalog\Models\ProductAttributeOption;
use App\Domains\ProductCatalog\Models\ProductCollection;
use App\Domains\ProductCatalog\Models\ProductCollectionItem;
use App\Domains\ProductCatalog\Models\ProductItem;
use App\Domains\ProductCatalog\Models\ProductItemIdentifier;
use App\Domains\ProductCatalog\Models\ProductItemPublishSchedule;
use App\Domains\ProductCatalog\Models\ProductItemSalePrice;
use App\Domains\ProductCatalog\Models\ProductType;
use App\Domains\ProductCatalog\Models\ProductUnit;
use App\Domains\Purchasing\Models\PurchaseInvoice;
use App\Domains\Purchasing\Models\PurchaseInvoiceLineItem;
use App\Domains\Purchasing\Models\PurchaseOrder;
use App\Domains\Purchasing\Models\PurchaseOrderLineItem;
use App\Domains\Tenants\Models\Store;
use App\Domains\Tenants\Models\Tag;
use App\Domains\Tenants\Models\TenantUser;
use App\Domains\Vendors\Models\Vendor;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class CreateTenantInitialTables extends Migration
{
    public function up(): void
    {
        $this->systemTables();
        $this->financeTables();
        $this->usersTables();
        $this->accountingTables();
        $this->productCatalogTables();
        $this->inventoryTables();
        $this->vendorsTables();
        $this->purchasingTables();
        $this->manufacturingTables();
        $this->customersTables();
        $this->checkoutTables();
        $this->marketingTables();
        $this->ordersTables();
    }

    protected function systemTables(): void
    {
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();

            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');

            $table->timestamp('failed_at')->useCurrent();
        });

        Schema::create('action_events', function (Blueprint $table) {
            $table->id();

            $table->char('batch_id', 36);
            $table->char('user_id', 26)->index();
            $table->string('name');
            $table->string('actionable_type');
            $table->char('actionable_id', 26);
            $table->string('target_type');
            $table->char('target_id', 26);
            $table->string('model_type');
            $table->char('model_id', 26)->nullable();
            $table->text('fields');
            $table->string('status', 25)->default('running');
            $table->text('exception');
            $table->text('original')->nullable();
            $table->text('changes')->nullable();

            $table->timestamps();

            $table->index(['actionable_type', 'actionable_id']);
            $table->index(['batch_id', 'model_type', 'model_id']);
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            Tag::addUlidColumn($table);

            $table->jsonb('name')->default('{}');
            $table->string('type')->nullable();
            $table->integer('sort_order')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('taggables', function (Blueprint $table) {
            $table->foreignIdFor(Tag::class)->constrained()
                ->onDelete('cascade');

            $table->morphs('taggable');

            $table->unique(['tag_id', 'taggable_id', 'taggable_type']);
        });

        Schema::create('media', function (Blueprint $table) {
            $table->id();

            $table->morphs('model');
            $table->uuid('uuid')->nullable();
            $table->string('collection_name');
            $table->string('name');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->string('disk');
            $table->string('conversions_disk')->nullable();
            $table->unsignedBigInteger('size');
            $table->json('manipulations')->default('{}');
            $table->json('custom_properties')->default('{}');
            $table->json('generated_conversions')->default('{}');
            $table->json('responsive_images')->default('{}');

            $table->unsignedInteger('sort_order')->nullable();

            $table->nullableTimestamps();
        });

        Schema::create('audits', function (Blueprint $table) {
            $table->id();

            $table->morphs('user');
            $table->morphs('auditable');

            $table->string('event');

            $table->text('old_values')->nullable();
            $table->text('new_values')->nullable();

            $table->text('url')->nullable();

            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent', 1023)->nullable();

            $table->string('tags')->nullable();

            $table->timestamps();
        });

        Schema::create('stored_events', function (Blueprint $table) {
            $table->char('id', 26)->unique();
            $table->primary('id');

            $table->string('name');

            $table->jsonb('data')->default('{}');
            $table->jsonb('metadata')->default('{}');

            $table->timestamps();
        });

        Schema::create('internal_comments', function (Blueprint $table) {
            $table->increments('id');

            $table->morphs('author');
            $table->morphs('commentable');

            $table->text('comment');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('global_identifier_codes', function (Blueprint $table) {
            $table->string('value');
        });
    }

    protected function financeTables(): void
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->string('code')->primary();

            $table->jsonb('name')->default('{}');
            $table->jsonb('symbol')->default('{}');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            ExchangeRate::addUlidColumn($table);

            $table->string('source_currency_code')
                ->references('code')->on('currencies');
            $table->string('target_currency_code')
                ->references('code')->on('currencies');
            $table->decimal('exchange_rate');
            $table->timestamp('active_from')->index();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('taxes', function (Blueprint $table) {
            $table->id();
            Tax::addUlidColumn($table);

            $table->jsonb('name')->default('{}');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tax_rates', function (Blueprint $table) {
            $table->id();
            TaxRate::addUlidColumn($table);

            $table->foreignIdFor(Tax::class)->constrained();

            $table->decimal('percentage');

            $table->timestamp('effective_since')->index();
            $table->timestamp('effective_until')->nullable()->index();

            $table->timestamps();
            $table->softDeletes();
        });

        $this->financeInitialData();
    }

    protected function financeInitialData(): void
    {
        Currency::query()->forceCreate([
            'code' => 'SAR',
            'name' => ['ar' => 'ريال سعودي', 'en' => 'Saudi Riyal'],
            'symbol' => ['ar' => '﷼', 'en' => 'SR'],
        ]);

        Currency::query()->forceCreate([
            'code' => 'USD',
            'name' => ['ar' => 'دولار أمريكي', 'en' => 'U.S. Dollar'],
            'symbol' => ['ar' => '$', 'en' => '$'],
        ]);

        ExchangeRate::query()->forceCreate([
            'source_currency_code' => 'SAR',
            'target_currency_code' => 'USD',
            'exchange_rate' => '3.75',
            'active_from' => Carbon::create(2000),
        ]);

        /** @var Tax $tax */
        $tax = Tax::query()->forceCreate([
            'name' => [
                'ar' => 'ضريبة القيمة المضافة',
                'en' => 'Value-Added Tax',
            ],
        ]);

        TaxRate::query()->forceCreate([
            'tax_id' => $tax->id,
            'percentage' => '0.05',
            'effective_since' => Carbon::create('2018-01-01', 'Asia/Riyadh'),
            'effective_until' => Carbon::create('2020-06-30', 'Asia/Riyadh')->endOfDay(),
        ]);

        TaxRate::query()->forceCreate([
            'tax_id' => $tax->id,
            'percentage' => '0.15',
            'effective_since' => Carbon::create('2020-07-01', 'Asia/Riyadh'),
        ]);
    }

    protected function usersTables(): void
    {
        Schema::create('tenant_users', function (Blueprint $table) {
            $table->id();
            TenantUser::addUlidColumn($table);

            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            $table->rememberToken();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tenant_user_password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            Role::addUlidColumn($table);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            Permission::addUlidColumn($table);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('role_permissions', function (Blueprint $table) {
            $table->foreignIdFor(Role::class)->constrained();
            $table->foreignIdFor(Permission::class)->constrained();
        });

        Schema::create('tenant_user_roles', function (Blueprint $table) {
            $table->foreignIdFor(TenantUser::class)->constrained();
            $table->foreignIdFor(Role::class)->constrained();
        });
    }

    protected function accountingTables(): void
    {
        Schema::create('accounting_accounts', function (Blueprint $table) {
            $table->id();

            $table->jsonb('name')->default('{}');
            $table->string('code')->unique();

            $table->string('zoho_account_id');
            $table->string('type');

            $table->foreignIdFor(AccountingAccount::class, 'parent_account_id')
                ->constrained(AccountingAccount::getTableName());

            $table->timestamps();
            $table->softDeletes();
        });
    }

    protected function productCatalogTables(): void
    {
        Schema::create('product_units', function (Blueprint $table) {
            $table->id();
            ProductUnit::addUlidColumn($table);

            $table->string('code')->unique();
            $table->jsonb('name')->default('{}');
            $table->jsonb('symbol')->default('{}');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            ProductAttribute::addUlidColumn($table);

            $table->string('code')->unique();
            $table->jsonb('name')->default('{}');
            $table->jsonb('description')->default('{}');

            $table->integer('sort_order')->default(1)->index();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('product_attribute_options', function (Blueprint $table) {
            $table->id();
            ProductAttributeOption::addUlidColumn($table);

            $table->foreignIdFor(ProductAttribute::class)->constrained();

            $table->string('code')->unique();
            $table->jsonb('name')->default('{}');
            $table->jsonb('description')->default('{}');

            $table->integer('sort_order')->default(1)->index();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('product_types', function (Blueprint $table) {
            $table->id();
            ProductType::addUlidColumn($table);

            $table->string('code')->unique();
            $table->jsonb('name')->default('{}');

            $table->integer('sort_order')->default(1)->index();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('product_type_attribute_associations', function (Blueprint $table) {
            $table->foreignIdFor(ProductType::class)->constrained();
            $table->foreignIdFor(ProductAttribute::class)->constrained();

            $table->integer('sort_order')->default(1)->index();

            $table->primary(['product_type_id', 'product_attribute_id']);

            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            Product::addUlidColumn($table);

            $table->foreignIdFor(ProductType::class)->constrained();
            $table->foreignIdFor(ProductUnit::class)->constrained();
            $table->bigInteger('store_id', false, true);

            $table->string('code')->nullable()->unique();
            $table->jsonb('name')->default('{}');
            $table->jsonb('description')->default('{}');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('product_attribute_associations', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Product::class)->constrained();
            $table->foreignIdFor(ProductAttribute::class)->constrained();

            $table->timestamps();
        });

        Schema::create('product_items', function (Blueprint $table) {
            $table->id();
            ProductItem::addUlidColumn($table);

            $table->foreignIdFor(Product::class)->constrained();
            $table->foreignIdFor(ProductUnit::class)->constrained();

            $table->string('code')->unique();
            $table->jsonb('name')->default('{}');

            $table->boolean('main')->default(false);
            $table->boolean('published')->default(false);

            $table->integer('sort_order')->default(1)->index();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('product_item_identifiers', function (Blueprint $table) {
            ProductItemIdentifier::addPrimaryUlidColumn($table);

            $table->foreignIdFor(ProductItem::class)->constrained();

            $table->string('type');
            $table->string('value');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('product_item_sale_prices', function (Blueprint $table) {
            ProductItemSalePrice::addPrimaryUlidColumn($table);

            $table->foreignIdFor(ProductItem::class)->constrained();

            ProductItemSalePrice::addMoneyColumns($table, 'price');

            $table->timestamp('effective_since')->index();
            $table->timestamp('effective_until')->nullable()->index();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('product_item_publish_schedules', function (Blueprint $table) {
            ProductItemPublishSchedule::addPrimaryUlidColumn($table);

            $table->foreignIdFor(ProductItem::class)->constrained();

            $table->timestamp('effective_since')->index();
            $table->timestamp('effective_until')->nullable()->index();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('product_item_attribute_option_associations', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(ProductItem::class)->constrained();
            $table->foreignIdFor(ProductAttributeOption::class)->constrained();

            $table->timestamps();
        });

        Schema::create('product_collections', function (Blueprint $table) {
            $table->id();
            ProductCollection::addUlidColumn($table);

            $table->bigInteger('store_id', false, true);
            $table->foreignIdFor(ProductCollection::class, 'parent_collection_id')->nullable()
                ->constrained(ProductCollection::getTableName());

            $table->string('code')->unique();
            $table->jsonb('name')->default('{}');
            $table->jsonb('description')->default('{}');

            $table->timestamp('published_at')->nullable();

            $table->integer('sort_order')->default(1)->index();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('product_collection_items', function (Blueprint $table) {
            $table->id();
            ProductCollectionItem::addUlidColumn($table);

            $table->foreignIdFor(ProductCollection::class)->constrained();
            $table->morphs('item');

            $table->integer('sort_order')->default(1)->index();

            $table->unique(['product_collection_id', 'item_id', 'item_type']);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    protected function inventoryTables(): void
    {
        Schema::create('inventory_locations', function (Blueprint $table) {
            $table->id();
            InventoryLocation::addUlidColumn($table);

            $table->jsonb('name')->default('{}');
            $table->string('code')->unique();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('inventory_areas', function (Blueprint $table) {
            $table->id();
            InventoryArea::addUlidColumn($table);

            $table->foreignIdFor(InventoryLocation::class)->constrained();

            $table->jsonb('name')->default('{}');
            $table->string('code')->unique();
            $table->string('type')->index();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(InventoryArea::class)->constrained();
            $table->foreignIdFor(ProductItem::class)->constrained();

            $table->bigInteger('stock')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            InventoryMovement::addUlidColumn($table);

            $table->foreignIdFor(InventoryArea::class)->constrained();
            $table->foreignIdFor(ProductItem::class)->constrained();

            $table->bigInteger('quantity')->default(0);
            $table->timestamp('occurred_at');

            $table->morphs('cause');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('transfer_orders', function (Blueprint $table) {
            $table->id();
            TransferOrder::addUlidColumn($table);

            $table->foreignIdFor(InventoryArea::class, 'source_area_id')
                ->constrained(InventoryArea::getTableName());
            $table->foreignIdFor(InventoryArea::class, 'target_area_id')
                ->constrained(InventoryArea::getTableName());

            $table->string('state')->index();
            $table->string('code')->nullable()->unique();
            $table->timestamp('issue_date')->nullable()->index();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('transfer_order_line_items', function (Blueprint $table) {
            $table->id();
            TransferOrderLineItem::addUlidColumn($table);

            $table->foreignIdFor(TransferOrder::class)->constrained();
            $table->foreignIdFor(ProductItem::class)->constrained();

            $table->bigInteger('quantity')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    protected function vendorsTables(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            Vendor::addUlidColumn($table);

            $table->string('code')->unique();
            $table->jsonb('name')->default('{}');
            $table->jsonb('company_name')->default('{}');
            $table->string('vat_account_number')->nullable();
            $table->string('cr_number')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('vendor_users', function (Blueprint $table) {
            $table->id();
            Vendor::addUlidColumn($table);

            $table->foreignIdFor(Vendor::class)->constrained();

            $table->string('code')->unique();
            $table->jsonb('name')->default('{}');
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            $table->rememberToken();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    protected function purchasingTables(): void
    {
        Schema::create('purchase_payment_terms', function (Blueprint $table) {
            $table->id();

            $table->jsonb('name')->default('{}');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            PurchaseOrder::addUlidColumn($table);

            $table->foreignIdFor(Vendor::class)->constrained();
            $table->foreignIdFor(InventoryArea::class, 'delivery_area_id')
                ->constrained(InventoryArea::getTableName());

            $table->string('code')->nullable()->unique();
            $table->string('state')->index();
            $table->timestamp('issue_date')->nullable();
            $table->timestamp('delivery_date')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('purchase_order_line_items', function (Blueprint $table) {
            $table->id();
            PurchaseOrderLineItem::addUlidColumn($table);

            $table->foreignIdFor(PurchaseOrder::class)->constrained();
            $table->foreignIdFor(ProductItem::class)->constrained();

            $table->bigInteger('quantity')->default(0);
            $table->bigInteger('quantity_delivered')->default(0);
            PurchaseOrderLineItem::addMoneyColumns($table, 'unit_price');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('purchase_invoices', function (Blueprint $table) {
            $table->id();
            PurchaseInvoice::addUlidColumn($table);

            $table->foreignIdFor(Vendor::class)->constrained();
            $table->foreignIdFor(PurchaseOrder::class)->constrained();

            $table->string('code')->nullable()->unique();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('purchase_invoice_line_items', function (Blueprint $table) {
            $table->id();
            PurchaseInvoiceLineItem::addUlidColumn($table);

            $table->foreignIdFor(Vendor::class)->constrained();
            $table->foreignIdFor(ProductItem::class)->constrained();

            $table->bigInteger('quantity')->default(0);
            PurchaseInvoiceLineItem::addMoneyColumns($table, 'unit_price');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    protected function manufacturingTables(): void
    {
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            WorkOrder::addUlidColumn($table);

            $table->string('code')->nullable()->unique();
            $table->string('state')->index();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('work_order_line_items', function (Blueprint $table) {
            $table->id();
            WorkOrderLineItem::addUlidColumn($table);

            $table->foreignIdFor(WorkOrder::class)->constrained();
            $table->foreignIdFor(ProductItem::class)->constrained();

            $table->bigInteger('quantity')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    protected function customersTables()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            Customer::addUlidColumn($table);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('customer_payment_methods', function (Blueprint $table) {
            $table->id();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    protected function checkoutTables(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            Cart::addUlidColumn($table);

            // $table->foreignIdFor(Store::class)->constrained();

            $table->jsonb('discounts')->default('{}');
            $table->jsonb('payment')->default('{}');

            $table->jsonb('fulfillment')->default('{}');
            $table->jsonb('refund')->default('{}');

            $table->jsonb('order_items')->default('{}');
            $table->jsonb('shipping_address')->default('{}');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    protected function marketingTables(): void
    {
        Schema::create('slugs', function (Blueprint $table) {
            Slug::addPrimaryUlidColumn($table);

            $table->string('path')->unique();
            $table->string('locale')->nullable()->index();
            $table->morphs('linked');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('promotions', function (Blueprint $table) {
            $table->id();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('promotion_rules', function (Blueprint $table) {
            $table->id();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('promotion_actions', function (Blueprint $table) {
            $table->id();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('content_pages', function (Blueprint $table) {
            $table->id();
            ContentPage::addUlidColumn($table);

            // $table->foreignIdFor(Store::class)->constrained();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('content_page_blocks', function (Blueprint $table) {
            $table->id();
            ContentPageBlock::addUlidColumn($table);

            $table->foreignIdFor(ContentPage::class)->constrained();

            $table->string('type');
            $table->jsonb('label');
            $table->jsonb('show_more_label');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('content_page_block_items', function (Blueprint $table) {
            $table->id();
            ContentPageBlockItem::addUlidColumn($table);

            $table->foreignIdFor(ContentPageBlock::class)->constrained();
            $table->numericMorphs('link');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    protected function ordersTables(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            Order::addUlidColumn($table);

            // $table->foreignIdFor(Store::class)->constrained();

            $table->json('order_items')->default('{}');

            // $order_number

            // $applied_promotions
            // $fulfillment

            // $processed_at

            // $billing_address
            // $payment_details

            // $processing_method
            // $payment_method

            // $refunds

            // $shipping_address

            // $source

            // $tags

            // $line_items
            // $tax_lines
            // $shipping_lines

            // $total_price
            // $total_line_items_price
            // $total_discounts

            // $cancel_reason
            // $cancelled_at

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('return_requests', function (Blueprint $table) {
            $table->id();
            Order::addUlidColumn($table);

            $table->timestamps();
            $table->softDeletes();
        });
    }
}
