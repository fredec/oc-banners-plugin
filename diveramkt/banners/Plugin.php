<?php namespace Diveramkt\Banners;

use System\Classes\PluginBase;
use Diveramkt\Banners\Classes\Functions;
use Event;
use Diveramkt\Banners\Classes\BackendHelpers;

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
                    if(!$settings->enabled_image_mobile) $widget->removeField('image_mobile');
                    if(!$settings->enabled_image_tablet) $widget->removeField('image_tablet');

                    if(!$settings->enabled_text_mobile){
                        $widget->removeField('section_texts_mobile');
                        $widget->removeField('title_mobile');
                        $widget->removeField('text_mobile');
                    }

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
                    if(!$settings->enabled_links_extra) $widget->removeField('links_extra');
                    if(!$settings->enabled_color_background) $widget->removeField('color_background');

                    if($settings->types_midias && count($settings->types_midias) > 0){
                        if(!in_array('filevideo', $settings->types_midias)) $widget->removeField('video');
                        if(!in_array('youtube', $settings->types_midias)) $widget->removeField('youtube');
                    }else{
                        // $widget->removeField('type');
                        $widget->removeField('youtube');
                        $widget->removeField('video');
                    }
                }
            }
        });


        if(BackendHelpers::isTranslate()){
            \Diveramkt\Banners\Models\Banners::extend(function($model) {
                if(!in_array('RainLab.Translate.Behaviors.TranslatableModel',$model->implement)) $model->implement[] = 'RainLab.Translate.Behaviors.TranslatableModel';
                $model->translatable = ['image','image_mobile','title','text','button','links_extra'];
            });
        }

    }

        /**
     * Returns plain PHP functions.
     *
     * @return array
     */
        private function getPhpFunctions()
        {
            return [
                'video_embed' => function($url, $autoplay=0) {
                    if(strpos("[".$url."]", "youtu.be/") || strpos("[".$url."]", "youtube")){
                        if(strpos("[".$url."]", "&feature")){
                            preg_match_all("#&feature(.*?)&#s", $url, $result);
                            if(isset($result[0][0])) $url=str_replace($result[0][0], '&', $url);
                            else{
                                $url=explode('&feature', $url);
                                $url=$url[0];
                            }
                        }
                        $retorno='';

                        if(strpos("[".$url."]", "&")){
                            $exp=explode('&', $url);
                            foreach ($exp as $key => $value) {
                                if($key > 0) $url=str_replace('&'.$value,'', $url);
                            }
                        }

                        if(strpos("[".$url."]", "watch?v=")) $retorno=str_replace('/watch?v=', '/embed/', str_replace('&feature=youtu.be','',$url));
                        elseif(strpos("[".$url."]", "youtu.be/")){
                            $exp=explode('youtu.be/', $url);
                            if(isset($exp[1])){
                                $retorno='https://www.youtube.com/embed/'.$exp[1];
                            }else $retorno=$url;
                        }else $retorno=$url;

                        $url=$retorno.'?controls=0&amp;start=1&amp;autoplay='.$autoplay.'&amp;loop=1&amp;background=1';
                        if($autoplay) $url=$url.'&mute=1';
                    }elseif(strpos("[".$url."]", "vimeo.com")){
                        $par=explode('/', $url);
                        $url='https://player.vimeo.com/video/'.end($par).'?autoplay='.$autoplay.'&loop=1&background=1';
                        if($autoplay) $url=$url.'&mute=1';
                    }
                    return $url;
                },
                'youtube_thumb' => function($url, $tamanho=1) {
                    $numero = 0;
                    if(strpos("[".$url."]", "embed")){
                        $exp=explode('/', $url);
                        $exp=explode('?',end($exp));
                        $url=array();
                        $url[0]='v='.$exp[0];
                    }else{
                        $url = str_replace('&', '&amp;', $url);
                        $url = explode('&amp;', $url);
                    }
                    if(isset($url[0])){
                        if($tamanho == 1){
                            return 'https://i1.ytimg.com/vi/' . substr(stristr($url[0], 'v='), 2) . '/' . $numero . '.jpg';
                        }elseif($tamanho == 2){
                            return 'https://i1.ytimg.com/vi/' . substr(stristr($url[0], 'v='), 2) . '/hqdefault.jpg';
                        }elseif($tamanho == 3){
                            return 'https://img.youtube.com/vi/' . substr(stristr($url[0], 'v='), 2) . '/mqdefault.jpg';
                        }elseif($tamanho == 4){
                            return 'https://img.youtube.com/vi/' . substr(stristr($url[0], 'v='), 2) . '/maxresdefault.jpg';
                        }
                    }
                    return false;
                },
            ];
        }

        public function registerMarkupTags()
        {
            $filters = [];
        // add PHP functions
            $filters += $this->getPhpFunctions();

            return [
                'filters'   => $filters,
            ];
        }


    }
