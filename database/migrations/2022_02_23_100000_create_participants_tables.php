<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('firstname', 100)->nullable();
            $table->string('email', 200)->index();
            $table->timestamp('email_verified')->nullable();
            $table->char('gender', 1)->nullable();
            $table->date('birthdate')->nullable();
            $table->string('location', 100)->nullable();
            $table->string('education', 100)->nullable();
            $table->string('education_topic', 100)->nullable();
            $table->string('language', 100)->nullable();
            $table->string('survey_languages', 100)->nullable();
            $table->string('study_interest', 100)->nullable();
            $table->timestamps();
        });

        Schema::create('studies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();
            $table->string('excludes', 200)->nullable();
            $table->timestamps();
        });

        Schema::create('participants_studies', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('participant_id')->unsigned();
            $table->bigInteger('study_id')->unsigned();

            $table->foreign('participant_id', 'participants_studies_participant_id')->references('id')->on('participants')->onDelete('cascade');
            $table->foreign('study_id', 'participants_studies_study_id')->references('id')->on('studies')->onDelete('cascade');
        });

        Schema::create('mailings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('study_id')->unsigned();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->string('name', 200);
            $table->string('description', 2000)->nullable();
            $table->tinyInteger('state')->unsigned()->nullable();
            $table->text('subject', 100)->nullable();
            $table->text('content');
            $table->timestamps();

            $table->foreign('study_id', 'mailings_study_id')->references('id')->on('studies')->onDelete('cascade');
            $table->foreign('user_id', 'mailings_user_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('mailings_participants', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('mailing_id')->unsigned();
            $table->bigInteger('participant_id')->unsigned();
            $table->timestamp('mail_sent')->nullable();

            $table->foreign('mailing_id', 'mailings_participants_mailing_id')->references('id')->on('mailings')->onDelete('cascade');
            $table->foreign('participant_id', 'mailings_participants_participant_id')->references('id')->on('participants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('participants_mailings');
        Schema::dropIfExists('mailings');
        Schema::dropIfExists('participants_studies');
        Schema::dropIfExists('studies');
        Schema::dropIfExists('participants');
    }
};
