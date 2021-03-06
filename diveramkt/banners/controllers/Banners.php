<?php namespace Diveramkt\Banners\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Banners extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController',        'Backend\Behaviors\ReorderController','Backend\Behaviors\RelationController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';
    public $relationConfig = 'config_relation.yaml';

    public $requiredPermissions = [
        'diveramktbanners_banners'
    ];

    public function __construct()
    {
        parent::__construct();
        // BackendMenu::setContext('Diveramkt.Banners', 'banners-main');
        BackendMenu::setContext('Diveramkt.Banners', 'banners-main', 'banners-banners');
    }

    public function reorderExtendQuery($query)
    {
        $query->orderBy('sort_order', 'desc');
        return $query;
    }

    public function listExtendQuery($query)
    {
        $query->orderBy('sort_order', 'desc');
        return $query;
    }
    
}
