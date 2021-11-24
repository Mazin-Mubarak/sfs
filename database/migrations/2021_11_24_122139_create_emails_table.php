<?php

use App\Models\Email;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('verification_token')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->bigInteger('created_by')->unsigned();
            $table->enum('type', Email::getTypes());
            $table->enum('associated_to', Email::getValidAssociations());
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
        Schema::dropIfExists('emails');
    }
}
