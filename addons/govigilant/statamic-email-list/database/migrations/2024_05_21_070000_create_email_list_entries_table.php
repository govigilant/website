<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('email_list_entries', function (Blueprint $table): void {
            $table->id();

            $table->string('list');
            $table->string('email');

            $table->json('data');

            $table->boolean('mail_sent')->default(false);
            $table->boolean('confirmed')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_list_entries');
    }
};
