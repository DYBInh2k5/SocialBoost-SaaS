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
        Schema::create('scheduled_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('content_template_id')->nullable()->constrained()->nullOnDelete();
            $table->string('platform');
            $table->text('post_text');
            $table->text('ai_caption')->nullable();
            $table->timestamp('scheduled_for');
            $table->timestamp('published_at')->nullable();
            $table->string('status')->default('draft');
            $table->unsignedInteger('engagement_score')->default(0);
            $table->timestamps();

            $table->index(['workspace_id', 'status', 'scheduled_for']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheduled_posts');
    }
};
