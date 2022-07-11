<?php namespace Diveramkt\Banners\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Categorias extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController',        'Backend\Behaviors\ReorderController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    public $requiredPermissions = [
        'diveramktbanners_categorias' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Diveramkt.Banners', 'banners-main', 'banners-categorias');
    }
}
