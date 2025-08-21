<?php

use App\Enums\ProjectStatusEnum;
use App\Models\Category;
use App\Models\User;
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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class,'created_by_id');
            $table->foreignIdFor(User::class,'updated_by_id');
            $table->foreignIdFor(Category::class);
            $table->string('name')->index();
            $table->text('description')->nullable();
            $table->text('location')->nullable();
            $table->string('status')->default(ProjectStatusEnum::DRAFT); // draft, active, completed, archived
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('budget', 15, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
