<?php namespace Diveramkt\Banners;

use System\Classes\PluginBase;
use Diveramkt\Banners\Classes\Functions;
use Event;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            'Diveramkt\Banners\Components\Banners' => 'Banners',
        ];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'diveramkt.banners::lang.plugin.name',
                'description' => '',
                'category'    => 'DiveraMkt',
                'icon'        => 'icon-image',
                'class'       => 'DiveraMkt\Banners\Models\Settings',
                'order'       => 500,
                'keywords'    => 'banners diveramkt',
                'permissions' => ['diveramktbanners_settings'],
            ]
        ];
    }

    public function boot(){
        Event::listen('backend.form.extendFields', function($widget) {
            if($widget->isNested === false){
                if($widget->model instanceof \Diveramkt\Banners\Models\Banners) {
                    $settings=Functions::getSettings();
                    if(isset($settings->enabled_image_mobile) && !$settings->enabled_image_mobile) $widget->removeField('image_mobile');

                    $positions=[ 'horizontal' => 1, 'vertical' => 1 ];
                    if(!$settings->enabled_position_text_options){
                        $widget->removeField('position');
                        $positions['horizontal']=0;
                    }
                    if(!$settings->enabled_position_vertical_text_options){
                        $widget->removeField('position_vertical');
                        $positions['vertical']=0;
                    }
                    if(!$positions['horizontal'] && !$positions['vertical']) $widget->removeField('section_position');
                    if(!$settings->enabled_text_color){
                        $widget->removeField('color_text');
                        $widget->removeField('section_style_text');
                    }
                    if(!$settings->enabled_button_color) $widget->removeField('color_button');
                }
            }
        });
    }


}
