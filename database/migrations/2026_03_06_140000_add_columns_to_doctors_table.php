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
        Schema::table('doctors', function (Blueprint $table) {
            if (!Schema::hasColumn('doctors', 'biography')) {
                $table->text('biography')->nullable()->after('specialty');
            }
            if (!Schema::hasColumn('doctors', 'qualifications')) {
                $table->string('qualifications')->nullable()->after('biography');
            }
            if (!Schema::hasColumn('doctors', 'rating')) {
                $table->decimal('rating', 3, 2)->default(0)->after('qualifications');
            }
            if (!Schema::hasColumn('doctors', 'total_reviews')) {
                $table->integer('total_reviews')->default(0)->after('rating');
            }
            if (!Schema::hasColumn('doctors', 'avatar')) {
                $table->string('avatar')->nullable()->after('total_reviews');
            }
            if (!Schema::hasColumn('doctors', 'is_available')) {
                $table->boolean('is_available')->default(true)->after('avatar');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $columns = ['biography', 'qualifications', 'rating', 'total_reviews', 'avatar', 'is_available'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('doctors', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
