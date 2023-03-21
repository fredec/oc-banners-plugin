<?php namespace Diveramkt\Banners\Classes;

use Backend, BackendAuth;
use System\Models\PluginVersion;

class BackendHelpers {

    /**
     * Check if Diveramkt Tranlate plugin is installed
     *
     * @return boolean
     */
    public static $getIsMiscelanious=null;
    public static function isMiscelanious() :bool {
        if(!Self::$getIsMiscelanious){
            $plugins=new PluginVersion();
            Self::$getIsMiscelanious=class_exists('\Diveramkt\Miscelanious\Plugin') && $plugins->where('code','Diveramkt.Miscelanious')->ApplyEnabled()->count();
        }
        return Self::$getIsMiscelanious;
    }

    public static $getIsTranslate=null;
    public static function isTranslate() :bool {
        if(!Self::$getIsTranslate){
            $plugins=new PluginVersion();
            Self::$getIsTranslate=class_exists('\RainLab\Translate\Classes\Translator') && class_exists('\RainLab\Translate\Models\Message') && $plugins->where('code','RainLab.Translate')->ApplyEnabled()->count();
        }
        return Self::$getIsTranslate;
    }

}

?>