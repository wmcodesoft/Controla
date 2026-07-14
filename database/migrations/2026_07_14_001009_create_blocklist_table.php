<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blocklist', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('reason', 255);
            $table->string('blockable_type', 50)->comment('visitor, vehicle, resident, member');
            $table->unsignedBigInteger('blockable_id');
            $table->foreignId('blocked_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('blocked_at')->useCurrent();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['client_id', 'is_active']);
            $table->index(['blockable_type', 'blockable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blocklist');
    }
};
