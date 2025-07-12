<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('order_id')->unique()->after('id');
            $table->string('name')->nullable()->after('email');
            $table->string('contact')->nullable()->after('name');
            $table->string('address')->nullable()->after('contact');
            $table->string('company')->nullable()->after('address');
            $table->string('website')->nullable()->after('company');
            $table->longText('message')->nullable()->after('website');
            $table->unsignedBigInteger('pricing_id')->nullable()->after('currency');

            // Add foreign key constraint
            $table->foreign('pricing_id')->references('id')->on('pricings')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['pricing_id']);
            $table->dropColumn([
                'order_id', 'name', 'contact', 'address',
                'company', 'website', 'message', 'pricing_id'
            ]);
        });
    }
};
