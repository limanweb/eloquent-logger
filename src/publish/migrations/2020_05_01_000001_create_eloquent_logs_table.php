<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEloquentLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eloquent_logs', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable()->comment('User ID');
            $table->string('ref_type')->comment('Model name or morph-alias');
            $table->uuid('ref_uuid_id')->nullable()->comment('UUID model-ID');
            $table->bigInteger('ref_int_id')->nullable()->comment('Integer model-ID');
            $table->string('operation')->comment('Model operation');
            $table->jsonb('details');
            $table->timestamps();
            
            $table->index(['ref_type', 'ref_uuid_id', 'ref_int_id'], 'model_audits_idx_model');
            $table->index(['user_id'], 'model_audits_idx_user');
        });
            
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eloquent_logs');
    }
}
