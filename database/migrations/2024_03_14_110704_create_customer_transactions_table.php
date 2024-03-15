<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customer_transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('transaction_type', array('purchase', 'return'));
            $table->double('quantity')->default(0);
            $table->string('remarks')->nullable()->comment('optional, for additional details about the transaction');
            $table->unsignedBigInteger('medication_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('medication_id', ('index_key_'.Str::uuid()->toString()))
                ->references('id')
                ->on('medications')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('customer_id', ('index_key_'.Str::uuid()->toString()))
                ->references('id')
                ->on('customers')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('created_by', ('index_key_'.Str::uuid()->toString()))
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_transactions');
    }
};
