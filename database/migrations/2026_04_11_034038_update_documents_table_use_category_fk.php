<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $defaults = [
            ['name' => 'AGM', 'slug' => 'agm', 'sort_order' => 1],
            ['name' => 'Prospectus', 'slug' => 'prospectus', 'sort_order' => 2],
            ['name' => 'Circular', 'slug' => 'circular', 'sort_order' => 3],
            ['name' => 'Other', 'slug' => 'other', 'sort_order' => 4],
        ];
        foreach ($defaults as $row) {
            DB::table('document_categories')->insert([
                ...$row,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $slugToId = DB::table('document_categories')->pluck('id', 'slug');

        Schema::table('documents', function (Blueprint $table) {
            $table->foreignId('document_category_id')
                ->nullable()
                ->after('title')
                ->constrained()
                ->nullOnDelete();
        });

        foreach ($slugToId as $slug => $id) {
            DB::table('documents')->where('category', $slug)->update(['document_category_id' => $id]);
        }

        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->enum('category', ['agm', 'prospectus', 'circular', 'other'])->default('other')->after('title');
        });

        $idToSlug = DB::table('document_categories')->pluck('slug', 'id');
        foreach ($idToSlug as $id => $slug) {
            if (in_array($slug, ['agm', 'prospectus', 'circular', 'other'], true)) {
                DB::table('documents')->where('document_category_id', $id)->update(['category' => $slug]);
            }
        }

        Schema::table('documents', function (Blueprint $table) {
            $table->dropConstrainedForeignId('document_category_id');
        });
    }
};
