<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL: ALTER TABLE untuk memperluas enum
        DB::statement("ALTER TABLE pengaduan MODIFY COLUMN status ENUM('1','2','3','4','5','6','7') NOT NULL DEFAULT '1'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pengaduan MODIFY COLUMN status ENUM('1','2','3','4','5','6') NOT NULL DEFAULT '1'");
    }
};
