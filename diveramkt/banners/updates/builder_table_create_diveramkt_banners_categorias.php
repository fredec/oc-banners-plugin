<?php namespace Diveramkt\Banners\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDiveramktBannersCategorias extends Migration
{
    public function up()
    {
        Schema::create('diveramkt_banners_categorias', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('title', 255)->nullable();
            $table->string('slug', 255)->nullable();
            $table->integer('sort_order')->nullable();
            $table->integer('enabled')->nullable()->default(1);
            $table->text('infos')->nullable();
        });
        Schema::create('diveramkt_banners_banners_categorias', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('categorias_id')->nullable();
            $table->integer('banners_id')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('diveramkt_banners_categorias');
        Schema::dropIfExists('diveramkt_banners_banners_categorias');
    }
}