<?php namespace Diveramkt\Banners\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDiveramktBannersBanners extends Migration
{
    public function up()
    {
        Schema::create('diveramkt_banners_banners', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->text('infos')->nullable();
            $table->integer('sort_order')->nullable();
            // $table->integer('enabled')->default(1)->nullable();
            $table->string('link',255)->nullable();
            $table->string('label',255)->nullable();
            $table->string('image', 100)->nullable();
            $table->string('image_mobile', 100)->nullable();
            $table->dateTime('date_begin')->nullable();
            $table->dateTime('date_end')->nullable();
            $table->integer('total_clicks')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('diveramkt_banners_banners');
    }
}