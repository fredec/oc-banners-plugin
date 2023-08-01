<?php namespace Diveramkt\Banners\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDiveramktBannersBanners2 extends Migration
{
    public function up()
    {
        Schema::table('diveramkt_banners_banners', function($table)
        {
            $table->string('youtube', 255)->nullable();
            $table->integer('type')->nullable()->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('diveramkt_banners_banners', function($table)
        {
            $table->dropColumn('youtube');
            $table->dropColumn('type');
        });
    }
}
