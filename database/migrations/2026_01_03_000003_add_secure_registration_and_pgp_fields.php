<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('name');
            $table->string('pin_hash')->nullable()->after('password');
            $table->text('public_pgp_key')->nullable()->after('bio');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->longText('encrypted_body')->nullable()->after('body');
            $table->foreignId('recipient_id')->nullable()->after('sender_id')->constrained('users')->nullOnDelete();
            $table->string('encryption_scheme')->default('pgp')->after('encrypted_body');
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropConstrainedForeignId('recipient_id');
            $table->dropColumn(['encrypted_body', 'encryption_scheme']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'pin_hash', 'public_pgp_key']);
        });
    }
};
