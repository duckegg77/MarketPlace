<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar_path')->nullable()->after('two_factor_secret');
            $table->text('bio')->nullable()->after('avatar_path');
            $table->boolean('is_blocked')->default(false)->after('bio');
        });

        Schema::table('sub_orders', function (Blueprint $table) {
            $table->string('selected_shipping_method')->nullable()->after('subtotal_cents');
            $table->unsignedBigInteger('shipping_cents')->default(0)->after('selected_shipping_method');
            $table->unsignedBigInteger('released_cents')->default(0)->after('shipping_cents');
            $table->boolean('buyer_marked_released')->default(false)->after('released_cents');
        });

        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->unsignedBigInteger('price_cents');
            $table->integer('estimated_days')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->nullableMorphs('favoritable');
            $table->timestamps();
            $table->unique(['user_id', 'favoritable_type', 'favoritable_id']);
        });

        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rater_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('rateable_user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('sub_order_id')->nullable()->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('stars');
            $table->text('comment')->nullable();
            $table->timestamps();
        });

        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_id')->constrained('users')->cascadeOnDelete();
            $table->nullableMorphs('reportable');
            $table->string('reason_code');
            $table->text('details')->nullable();
            $table->string('status')->default('open');
            $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blocker_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('blocked_id')->constrained('users')->cascadeOnDelete();
            $table->string('reason')->nullable();
            $table->timestamps();
            $table->unique(['blocker_id', 'blocked_id']);
        });

        Schema::create('currency_rates', function (Blueprint $table) {
            $table->id();
            $table->string('base_currency', 3)->default('USD');
            $table->string('quote_currency', 10);
            $table->decimal('rate', 20, 8);
            $table->timestamp('as_of');
            $table->timestamps();
            $table->unique(['base_currency', 'quote_currency']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('currency_rates');
        Schema::dropIfExists('blocks');
        Schema::dropIfExists('reports');
        Schema::dropIfExists('ratings');
        Schema::dropIfExists('favorites');
        Schema::dropIfExists('shipping_methods');

        Schema::table('sub_orders', function (Blueprint $table) {
            $table->dropColumn(['selected_shipping_method', 'shipping_cents', 'released_cents', 'buyer_marked_released']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar_path', 'bio', 'is_blocked']);
        });
    }
};
