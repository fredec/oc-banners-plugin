<?php namespace Diveramkt\Banners\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDiveramktBannersBanners extends Migration
{
    public function up()
    {
        Schema::table('diveramkt_banners_banners', function($table)
        {
            $table->string('image_tablet', 255)->nullable();
            $table->string('image', 255)->change();
            $table->string('image_mobile', 255)->change();
        });
    }
    
    public function down()
    {
        Schema::table('diveramkt_banners_banners', function($table)
        {
            $table->dropColumn('image_tablet');
            $table->string('image', 100)->change();
            $table->string('image_mobile', 100)->change();
        });
    }
}