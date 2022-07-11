<?php namespace Diveramkt\Banners;

use System\Classes\PluginBase;

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
    }
}
