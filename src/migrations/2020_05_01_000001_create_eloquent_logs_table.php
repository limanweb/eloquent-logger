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
            $userIdMethod = config('limanweb.eloquent_logger.user.key_create_method', 'bigInteger');
            
            $table->bigIncrements('id');
            $table->$userIdMethod('user_id')->nullable()->comment('User ID');
            $table->string('ref_type')->comment('Model name or morph-alias');
            $table->uuid('ref_uuid_id')->nullable()->comment('UUID model-ID');
            $table->bigInteger('ref_int_id')->nullable()->comment('Integer model-ID');
            $table->string('operation')->comment('Model operation');
            $table->jsonb('details');
            $table->timestamp('created_at');
            
            $table->index(['created_at', 'id'], 'eloquent_logs_idx_ref_created_at');
            $table->index(['ref_type', 'ref_uuid_id', 'ref_int_id', 'created_at', 'id'], 'eloquent_logs_idx_ref_model');
            $table->index(['user_id', 'created_at', 'id'], 'eloquent_logs_idx_user_id');
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
