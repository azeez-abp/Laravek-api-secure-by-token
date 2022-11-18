<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_manipulations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 225)->nullable();
            $table->string('path', 2000)->nullable();
            $table->string('type', 25)->nullable();
            $table->text('data');
            $table->string('output_path', 2000)->nullable();
            $table->foreignIdFor(App\Models\Album::class, 'album_id');
            $table->foreignIdFor(App\Models\User::class, 'user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('image_manipulations');
    }
};
