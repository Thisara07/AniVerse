<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add the fullName column if it doesn't exist
        $columns = Schema::getColumnListing('users');
        if (!in_array('fullName', $columns)) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('fullName')->after('id'); // Add new column
            });
            
            // Copy data from 'name' to 'fullName' if 'name' exists
            if (in_array('name', $columns)) {
                DB::statement('UPDATE users SET fullName = name');
                
                Schema::table('users', function (Blueprint $table) {
                    $table->dropColumn('name'); // Drop old column after copying data
                });
            }
        }

        // Add role and phoneNo columns if they don't exist
        $columns = Schema::getColumnListing('users');
        if (!in_array('role', $columns)) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('customer')->after('email');
            });
        }
        
        if (!in_array('phoneNo', $columns)) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('phoneNo')->default('')->after('role');
            });
        }

        // Drop columns if they exist
        if (in_array('email_verified_at', $columns)) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('email_verified_at');
            });
        }
        
        if (in_array('remember_token', $columns)) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('remember_token');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back the original columns if they don't exist
        $columns = Schema::getColumnListing('users');
        
        if (!in_array('name', $columns)) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('name')->after('id'); // Add back old column
            });
        }
        
        // Copy data from 'fullName' to 'name' if fullName exists
        if (in_array('fullName', $columns)) {
            DB::statement('UPDATE users SET name = fullName');
            
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('fullName'); // Drop new column after copying data
            });
        }

        // Add back original columns if they don't exist
        if (!in_array('email_verified_at', $columns)) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('email_verified_at')->nullable();
            });
        }
        
        if (!in_array('remember_token', $columns)) {
            Schema::table('users', function (Blueprint $table) {
                $table->rememberToken();
            });
        }

        // Drop the added columns if they exist
        if (in_array('role', $columns)) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['role', 'phoneNo']);
            });
        }
    }
};