    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up()
        {
            Schema::create('patients', function (Blueprint $table) {
                $table->id();
                $table->string('patient_id')->unique();
                $table->string('first_name');
                $table->string('last_name');
                $table->string('email')->unique()->nullable();
                $table->string('phone')->nullable();
                   $table->string('password')->nullable();
                $table->date('date_of_birth');
                $table->enum('gender', ['male', 'female', 'other']);
                $table->text('address')->nullable();
                $table->string('blood_group')->nullable();
                $table->text('medical_history')->nullable();
                $table->text('allergies')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        public function down()
        {
            Schema::dropIfExists('patients');
        }
    };