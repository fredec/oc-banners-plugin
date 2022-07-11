<?php namespace Diveramkt\Banners\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDiveramktBannersClicks extends Migration
{
    public function up()
    {
        Schema::create('diveramkt_banners_clicks', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->integer('banners_id')->nullable();
            $table->text('url')->nullable();
            $table->date('date_create')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('diveramkt_banners_clicks');
    }
}