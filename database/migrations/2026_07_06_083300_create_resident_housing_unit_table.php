<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resident_housing_unit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained()->cascadeOnDelete();
            $table->foreignId('housing_unit_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_primary')->default(false);
            $table->string('relationship_type', 30)->default('propietario');
            $table->timestamps();
            $table->unique(['resident_id', 'housing_unit_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resident_housing_unit');
    }
};
