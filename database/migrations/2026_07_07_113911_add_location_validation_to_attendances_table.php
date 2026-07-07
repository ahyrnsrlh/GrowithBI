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
        Schema::table('attendances', function (Blueprint $table) {
            $table->float('gps_accuracy')->nullable()->after('longitude');
            $table->float('coordinate_stability')->nullable()->after('gps_accuracy');
            $table->enum('location_validation_status', ['passed', 'failed', 'suspicious'])->default('passed')->after('coordinate_stability');
            $table->text('location_validation_notes')->nullable()->after('location_validation_status');
            $table->boolean('suspicious_location')->default(false)->after('location_validation_notes');
            $table->timestamp('validation_timestamp')->nullable()->after('suspicious_location');
            $table->decimal('checkout_latitude', 10, 8)->nullable()->after('photo_checkout');
            $table->decimal('checkout_longitude', 11, 8)->nullable()->after('checkout_latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn([
                'gps_accuracy',
                'coordinate_stability',
                'location_validation_status',
                'location_validation_notes',
                'suspicious_location',
                'validation_timestamp',
                'checkout_latitude',
                'checkout_longitude'
            ]);
        });
    }
};
