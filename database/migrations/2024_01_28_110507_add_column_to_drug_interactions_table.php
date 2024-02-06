<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToDrugInteractionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('drug_interactions', function (Blueprint $table) {
            $table->integer('drug_id')->nullable()->after('id');
            $table->integer('interaction_id')->nullable()->after('id');
            $table->integer('severity')->nullable();
            $table->json('dataset')->nullable();
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('drug_interactions', function (Blueprint $table) {
            $table->dropColumn('drug_id');
            $table->dropColumn('interaction_id');
            $table->dropColumn('severity');
            $table->dropColumn('dataset');
        });
    }
}