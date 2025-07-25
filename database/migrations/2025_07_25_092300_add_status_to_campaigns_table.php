<?php
// database/migrations/2025_07_25_092300_add_status_to_campaigns_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft')->after('end_date');
        });
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
