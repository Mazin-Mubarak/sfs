<?php

use App\Models\PhoneNumber;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhoneNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phone_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->string('verification_token')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->bigInteger('created_by')->unsigned();
            $table->enum('type', PhoneNumber::getTypes());
            $table->enum('associated_to', PhoneNumber::getValidAssociations());
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('phone_numbers');
    }
}
