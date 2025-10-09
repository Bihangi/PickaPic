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
        Schema::table('portfolios', function (Blueprint $table) {
            // Ensure photographer_id exists and add foreign key
            if (!Schema::hasColumn('portfolios', 'photographer_id')) {
                $table->foreignId('photographer_id')
                    ->after('id')
                    ->constrained('photographers')
                    ->onDelete('cascade');
                $table->index('photographer_id');
            }

            if (!Schema::hasColumn('portfolios', 'file_path')) {
                $table->string('file_path', 500)->after('photographer_id');
            }

            if (!Schema::hasColumn('portfolios', 'original_name')) {
                $table->string('original_name')->nullable()->after('file_path');
            }

            if (!Schema::hasColumn('portfolios', 'file_size')) {
                $table->integer('file_size')->nullable()->after('original_name');
            }

            if (!Schema::hasColumn('portfolios', 'mime_type')) {
                $table->string('mime_type', 100)->nullable()->after('file_size');
            }

            if (!Schema::hasColumn('portfolios', 'title')) {
                $table->string('title', 255)->nullable()->after('mime_type');
            }

            if (!Schema::hasColumn('portfolios', 'description')) {
                $table->text('description')->nullable()->after('title');
            }

            if (!Schema::hasColumn('portfolios', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('description');
                $table->index('is_featured');
            }

            // Add index for created_at if timestamps exist
            if (Schema::hasColumn('portfolios', 'created_at')) {
                $table->index('created_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('portfolios', function (Blueprint $table) {
            // Drop only the added columns/constraints
            if (Schema::hasColumn('portfolios', 'photographer_id')) {
                $table->dropForeign(['photographer_id']);
                $table->dropColumn('photographer_id');
            }

            if (Schema::hasColumn('portfolios', 'file_path')) {
                $table->dropColumn('file_path');
            }

            if (Schema::hasColumn('portfolios', 'original_name')) {
                $table->dropColumn('original_name');
            }

            if (Schema::hasColumn('portfolios', 'file_size')) {
                $table->dropColumn('file_size');
            }

            if (Schema::hasColumn('portfolios', 'mime_type')) {
                $table->dropColumn('mime_type');
            }

            if (Schema::hasColumn('portfolios', 'title')) {
                $table->dropColumn('title');
            }

            if (Schema::hasColumn('portfolios', 'description')) {
                $table->dropColumn('description');
            }

            if (Schema::hasColumn('portfolios', 'is_featured')) {
                $table->dropColumn('is_featured');
            }

            // Drop indexes safely
            $table->dropIndex(['is_featured']);
            $table->dropIndex(['created_at']);
        });
    }
};
