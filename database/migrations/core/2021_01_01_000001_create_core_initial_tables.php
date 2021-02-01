<?php

use App\Domains\Tenants\Models\Store;
use App\Domains\Tenants\Models\Tenant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoreInitialTables extends Migration
{
    public function up(): void
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

        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            Tenant::addUlidColumn($table);

            $table->jsonb('name');
            $table->string('domain')->unique();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            Store::addUlidColumn($table);

            $table->foreignIdFor(Tenant::class)->constrained();

            $table->string('code')->unique();
            $table->jsonb('name')->default('{}');
            $table->string('domain')->unique();

            $table->timestamps();
            $table->softDeletes();
        });
    }
}
