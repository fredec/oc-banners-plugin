<?php namespace Diveramkt\Banners\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDiveramktBannersCategorias extends Migration
{
    public function up()
    {
        Schema::table('diveramkt_banners_categorias', function($table)
        {
            $table->text('description')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('diveramkt_banners_categorias', function($table)
        {
            $table->dropColumn('description');
        });
    }
}
