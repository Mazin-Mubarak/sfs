<?php

use App\Models\InstitutionEmployee;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutionEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institution_employees', function (Blueprint $table) {
            $defaultRole = InstitutionEmployee::getDefaultRole();
            $validRoles = InstitutionEmployee::getValidRoles();

            $validStatuses = InstitutionEmployee::getValidStatuses();
            $defaultStatus = InstitutionEmployee::getDefaultStatus();

            $table->id();
            $table->index('user_id');
            $table->index('institution_id');
            $table->enum('role', $validRoles)->default($defaultRole);
            $table->enum('status', $validStatuses)->default($defaultStatus);
            $table->timestamps();

            

            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('institution_id')->references('id')->on('educational_institutions')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('institution_employees');
    }
}
