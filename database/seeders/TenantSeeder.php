<?php

namespace Database\Seeders;

use App\Domains\Inventory\Importers\InventoryAreasImporter;
use App\Domains\Inventory\Importers\InventoryItemsInitialStockImport;
use App\Domains\Inventory\Importers\InventoryLocationsImporter;
use App\Domains\Inventory\Models\InventoryArea;
use App\Domains\Inventory\Models\TransferOrder;
use App\Domains\ProductCatalog\Imports\ProductAttributeOptionsImport;
use App\Domains\ProductCatalog\Imports\ProductAttributesImport;
use App\Domains\ProductCatalog\Imports\ProductItemsImport;
use App\Domains\ProductCatalog\Imports\ProductsImport;
use App\Domains\ProductCatalog\Imports\ProductTypeAttributeAssociationsImport;
use App\Domains\ProductCatalog\Imports\ProductTypesImport;
use App\Domains\ProductCatalog\Imports\ProductUnitsImport;
use App\Domains\ProductCatalog\Models\ProductItem;
use App\Domains\Purchasing\Models\PurchaseOrder;
use App\Domains\Purchasing\States\PurchaseOrderState\Transitions\DraftToIssued;
use App\Domains\Tenants\Models\TenantUser;
use App\Domains\Vendors\Imports\VendorsImport;
use App\Domains\Vendors\Imports\VendorUsersImport;
use App\Domains\Vendors\Models\Vendor;
use Brick\Math\RoundingMode;
use Brick\Money\Money;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Validators\ValidationException;
use Spatie\ModelStates\Exceptions\CouldNotPerformTransition;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        TenantUser::query()
            ->updateOrCreate(['email' => 'test@nomoo.app'], [
                'name' => 'Main User',
                'email' => 'test@nomoo.app',
                'password' => bcrypt('password'),
            ]);

        $this->seedProductCatalogData();
        $this->seedBackOfficeData();
    }

    protected function seedProductCatalogData(): void
    {
        try {
            (new ProductUnitsImport())->import('fixtures/01-product_units.csv');
            (new ProductAttributesImport())->import('fixtures/02-product_attributes.csv');
            (new ProductAttributeOptionsImport())->import('fixtures/03-product_attribute_options.csv');
            (new ProductTypesImport())->import('fixtures/04-product_types.csv');
            (new ProductTypeAttributeAssociationsImport())
                ->import('fixtures/05-product_type_attribute_associations.csv');
            (new ProductsImport())->import('fixtures/06-products.csv');
            (new ProductItemsImport())->import('fixtures/07-product_items.csv');
        } catch (ValidationException $e) {
            foreach ($e->failures() as $failure) {
                dump($failure->toArray());
            }

            throw $e;
        }
    }

    protected function seedBackOfficeData(): void
    {
        try {
            (new InventoryLocationsImporter())->import('fixtures/10-inventory_locations.csv');
            (new InventoryAreasImporter())->import('fixtures/11-inventory_areas.csv');
            (new InventoryItemsInitialStockImport(
                InventoryArea::firstOrFailByCode('IA-02001'),
                Carbon::parse('2020-01-01', 'Asia/Riyadh'),
            ))->import('fixtures/16-inventory_items_link_20200101.csv');
            (new InventoryItemsInitialStockImport(
                InventoryArea::firstOrFailByCode('IA-03001'),
                Carbon::parse('2020-01-01', 'Asia/Riyadh'),
            ))->import('fixtures/15-inventory_items_salasa_20200101.csv');
        } catch (ValidationException $e) {
            foreach ($e->failures() as $failure) {
                dump($failure->toArray());
            }

            throw $e;
        }

        try {
            (new VendorsImport())->import('fixtures/20-vendors.csv');
            (new VendorUsersImport())->import('fixtures/21-vendor_users.csv');
        } catch (ValidationException $e) {
            foreach ($e->failures() as $failure) {
                dump($failure->toArray());
            }

            throw $e;
        }

        return;
        /** @var Vendor $vendor */
        $vendor = Vendor::query()->firstOrFail();

        /** @var InventoryArea $area */
        $area = InventoryArea::query()
            ->where('code', 'RUH1-RCV')
            ->firstOrFail();

        /** @var PurchaseOrder $po1 */
        $po1 = PurchaseOrder::query()
            ->updateOrCreate(['ulid' => '01EVD89EBYQT6AZJKSHXKSWDQB'], [
                'vendor_id' => $vendor->id,
                'delivery_area_id' => $area->id,
                'delivery_date' => '2020-01-01',
                'issue_date' => '2020-01-01',
            ]);

        $po1->lineItems()
            ->create([
                'product_item_id' => ProductItem::query()->find(1)->getKey(),
                'quantity' => 10,
                'unit_price' => Money::of(100, 'SAR', null, RoundingMode::HALF_EVEN),
            ]);

        $po1->lineItems()
            ->create([
                'product_item_id' => ProductItem::query()->find(2)->getKey(),
                'quantity' => 5,
                'unit_price' => Money::of(120, 'SAR', null, RoundingMode::HALF_EVEN),
            ]);

        try {
            $po1->state->transition(new DraftToIssued($po1, Carbon::now()->toDateString()));
        } catch (CouldNotPerformTransition) {
        }

        /** @var TransferOrder $to1 */
        $to1 = TransferOrder::query()
            ->updateOrCreate(['ulid' => '01EVD89EBYQT6AZJKSHXKSWDQB'], [
                'source_area_id' => $vendor->getPurchaseOrdersArea()->id,
                'target_area_id' => $po1->delivery_area_id,
                'issue_date' => '2020-01-01',
            ]);

        $to1->lineItems()
            ->create([
                'product_item_id' => ProductItem::query()->find(1)->getKey(),
                'quantity' => 10,
            ]);

        $to1->lineItems()
            ->create([
                'product_item_id' => ProductItem::query()->find(2)->getKey(),
                'quantity' => 5,
            ]);

        /** @var PurchaseOrder $po2 */
        $po2 = PurchaseOrder::query()
            ->updateOrCreate(['ulid' => '01EVEV2Q0C4V9VFXRTATCAS0HB'], [
                'vendor_id' => $vendor->id,
                'delivery_area_id' => $area->id,
                'delivery_date' => '2020-01-01',
                'issue_date' => '2020-01-01',
            ]);

        $po2->lineItems()
            ->create([
                'product_item_id' => ProductItem::query()->find(1)->getKey(),
                'quantity' => 10,
                'unit_price' => Money::of(100, 'SAR', null, RoundingMode::HALF_EVEN),
            ]);

        $po2->lineItems()
            ->create([
                'product_item_id' => ProductItem::query()->find(2)->getKey(),
                'quantity' => 5,
                'unit_price' => Money::of(120, 'SAR', null, RoundingMode::HALF_EVEN),
            ]);
    }
}
