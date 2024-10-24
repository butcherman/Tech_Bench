<?php

use App\Models\FileUpload;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('file_uploads', function (Blueprint $table) {
            $table->bigInteger('file_size')->after('file_name');
        });

        // Add the file size of each current file
        $allFiles = FileUpload::all();
        foreach ($allFiles as $fileData) {
            $path = $fileData->folder.DIRECTORY_SEPARATOR.$fileData->file_name;
            $size = -1;

            if (Storage::disk($fileData->disk)->exists($path)) {
                $size = Storage::disk($fileData->disk)
                    ->size($path);
            }

            $fileData->file_size = $size;
            $fileData->save();

        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('file_uploads', function (Blueprint $table) {
            $table->dropColumn('file_size');
        });
    }
};
