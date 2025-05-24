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
        /* votes() have */
        /*     - PK vote_id */
        /*     - FK user_id */
        /*     - FK idea_id */
        /*     - vote(can be +1 or -1) -> not decided yet */
        /*     - date_time() */
        Schema::create('vote', function (Blueprint $table) {
            $table->id();
            $table->enum('vote_type', ['upvote', 'downvote']);
            $table->foreignId('user_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('idea_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unique(['user_id', 'idea_id']);

            $table->index(['idea_id', 'vote_type']);
            $table->index('user_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     */
    public function down(): void
    {
        Schema::dropIfExists('vote');
    }
};
