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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('id_message_wp');
            $table->text('body');
            $table->integer('ack');
            $table->string('from_me');
            $table->string('to');
            $table->string('media_type');
            $table->string('media_path')->nullable();
            $table->string('timestamp_wp');
            $table->integer('is_private');
            $table->string('state');
            $table->foreignId('deleted_by')->nullable();
            $table->foreign('deleted_by')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('created_by');
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('chat_id');
            $table->foreign('chat_id')
                ->references('id')
                ->on('chats')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('tag_id')->nullable();
            $table->foreign('tag_id')
                ->references('id')
                ->on('tags')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};