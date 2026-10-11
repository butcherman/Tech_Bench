<?php

use App\Models\FileUpload;
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
        Schema::table('file_uploads', function (Blueprint $table) {
            $table->text('hash_name')->after('file_name');
        });

        $uploads = FileUpload::all();

        // Populate any existing file uploads to match the filename.
        foreach ($uploads as $upload) {
            if (blank($upload->hash_name)) {
                $upload->hash_name = $upload->file_name;
                $upload->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('file_uploads', function (Blueprint $table) {
            $table->dropColumn('hash_name');
        });
    }
};
