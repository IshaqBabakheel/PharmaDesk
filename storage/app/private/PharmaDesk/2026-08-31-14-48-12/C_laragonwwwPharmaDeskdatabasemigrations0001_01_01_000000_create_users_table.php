<?php

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
        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */

        Schema::create('users', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */

            $table->string('name');

            $table->string('email')
                ->unique();

            $table->string('phone', 20)
                ->nullable();

            $table->string('gender', 20)
                ->nullable();

            $table->date('date_of_birth')
                ->nullable();

            $table->string('address')
                ->nullable();

            $table->string('city')
                ->nullable();

            $table->string('country')
                ->default('Pakistan');

            /*
            |--------------------------------------------------------------------------
            | Authentication
            |--------------------------------------------------------------------------
            */

            $table->timestamp('email_verified_at')
                ->nullable();

            $table->string('password');

            $table->rememberToken();

            /*
            |--------------------------------------------------------------------------
            | Profile
            |--------------------------------------------------------------------------
            */

            $table->string('profile_photo')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->boolean('status')
                ->default(true);

            /*
            |--------------------------------------------------------------------------
            | Audit Information
            |--------------------------------------------------------------------------
            */

            $table->foreignId('created_by')
                ->nullable();

            $table->foreignId('updated_by')
                ->nullable();

            $table->foreignId('deleted_by')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Soft Deletes & Timestamps
            |--------------------------------------------------------------------------
            */

            $table->softDeletes();

            $table->timestamps();
        });


        /*
        |--------------------------------------------------------------------------
        | Self-referencing Foreign Keys
        |--------------------------------------------------------------------------
        */

        Schema::table('users', function (Blueprint $table) {

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->foreign('deleted_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });


        /*
        |--------------------------------------------------------------------------
        | Password Reset Tokens
        |--------------------------------------------------------------------------
        */

        Schema::create('password_reset_tokens', function (Blueprint $table) {

            $table->string('email')
                ->primary();

            $table->string('token');

            $table->timestamp('created_at')
                ->nullable();
        });


        /*
        |--------------------------------------------------------------------------
        | Sessions
        |--------------------------------------------------------------------------
        */

        Schema::create('sessions', function (Blueprint $table) {

            $table->string('id')
                ->primary();

            $table->foreignId('user_id')
                ->nullable()
                ->index();

            $table->string('ip_address', 45)
                ->nullable();

            $table->text('user_agent')
                ->nullable();

            $table->longText('payload');

            $table->integer('last_activity')
                ->index();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Drop users foreign keys first
        |--------------------------------------------------------------------------
        */

        Schema::table('users', function (Blueprint $table) {

            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropForeign(['deleted_by']);
        });


        /*
        |--------------------------------------------------------------------------
        | Drop tables
        |--------------------------------------------------------------------------
        */

        Schema::dropIfExists('sessions');

        Schema::dropIfExists('password_reset_tokens');

        Schema::dropIfExists('users');
    }
};