<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_number')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('id_number')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('phone');
            $table->text('address');
            $table->date('date_of_birth');
            $table->string('gender');
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->string('position');
            $table->string('employment_type'); // permanent, contract, temporary
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('basic_salary', 10, 2);
            $table->string('status')->default('active'); // active, inactive, terminated
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_employees');
    }
};
