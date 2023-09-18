<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('apply_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('job_uuid');
            $table->foreignId('user_id');
            $table->longText('letter');
            $table->string('attachment');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('apply_jobs');
    }
};
