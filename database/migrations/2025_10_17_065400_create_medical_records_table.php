    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up()
        {
            Schema::create('medical_records', function (Blueprint $table) {
                $table->id();
                $table->string('record_id')->unique();
                $table->foreignId('patient_id')->constrained()->onDelete('cascade');
                $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
                $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null');
                $table->date('visit_date');
                $table->text('symptoms');
                $table->text('diagnosis');
                $table->text('treatment');
                $table->text('prescription')->nullable();
                $table->text('tests_recommended')->nullable();
                $table->text('notes')->nullable();
                $table->decimal('weight', 5, 2)->nullable();
                $table->decimal('height', 5, 2)->nullable();
                $table->integer('blood_pressure_systolic')->nullable();
                $table->integer('blood_pressure_diastolic')->nullable();
                $table->decimal('temperature', 4, 2)->nullable();
                $table->integer('heart_rate')->nullable();
                $table->timestamps();
            });
        }

        public function down()
        {
            Schema::dropIfExists('medical_records');
        }
    };