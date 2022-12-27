<?php namespace Diveramkt\Banners\Models;

use Model;
use Diveramkt\Banners\Classes\Functions;
use Request;
use Db;
use Diveramkt\Banners\Models\Clicks;
// use Media\Classes\MediaLibrary;
use Config;

/**
 * Model
 */
class Banners extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    // public $attachOne = [
    //     'banner' => 'System\Models\File',
    // ];

    public $jsonable = ['infos'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'diveramkt_banners_banners';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'image' => 'required',
        'label' => 'required',
    ];

    public $belongsToMany = [
        'categorias' => [
            'table' => 'diveramkt_banners_banners_categorias',
            'Diveramkt\Banners\Models\Categorias',
        ],
    ];

    public $hasMany = [
        'clicks' => 'Diveramkt\Banners\Models\Clicks',
    ];

    public function scopeOrder($query){
        return $query->orderBy($this->table.'.sort_order','desc');
    }
    public function scopeEnabled($query){
        return $query
        ->select($this->table.'.*')
        // ->where($this->table.'.enabled',1)
        ->where($this->table.'.date_begin','<=',date('Y-m-d H:i:s'))
        ->where(function($query){
            $query->whereNull($this->table.'.date_end')->orWhere($this->table.'.date_end','>',date('Y-m-d H:i:s'));
        })->order();
    }

    public function getEnabledAttribute(){
        if($this->date_begin && $this->date_begin <= date('Y-m-d H:i:s') && ($this->date_end >= date('Y-m-d H:i:s') || !$this->date_end)) return true;
    }
    public function getTotalclickAttribute(){
        return Clicks::where('banners_id',$this->id)->count();
    }
    public function getBannerAttribute(){
        if(empty($this->image)) return;
        return Config::get('cms.storage.media.path').$this->image;
    }
    public function getBannerMobileAttribute(){
        if(empty($this->image_mobile)) return;
        return Config::get('cms.storage.media.path').$this->image_mobile;
    }

    public function beforeSave($model=false){
        $infos=$this->infos;
        foreach ($this->attributes as $key => $value) {
            if(!\Schema::hasColumn($this->table, $key)){
                $infos[$key]=$value;
                unset($this->$key);
            }
        }
        $this->infos=$infos;
    }

    public function afterFetch(){
        $attributes=$this->attributes;
        if(isset($this->infos) && is_array($this->infos) && count($this->infos)){
            foreach ($this->infos as $key => $value) {
                if(isset($attributes[$key])) continue;
                if(is_array($value)) $attributes[$key]=json_encode($value);
                else $attributes[$key]=$value;
            }
        }
        $this->attributes=$attributes;
    }

    public function getUrlAttribute(){
        if(!empty($this->link)){
            $url='';
            if($this->type_link == 'link') $url=Functions::prep_url($this->link);
            elseif($this->type_link == 'phone') $url=Functions::phone_link($this->link);
            elseif($this->type_link == 'whatsapp') $url=Functions::whats_link($this->link);
            return $url;
        }
    }
    public function getTargetAttribute(){
        if(!empty($this->url)) return Functions::target($this->url);
    }
    public function getLinkExternAttribute(){
        if(!strpos("[".$this->link."]", "url('/')")) return 1;
        else return 0;
    }

    public function getPositionOptions(){
        $settings=Functions::getSettings();
        $return=[];
        $options=[];
        if(isset($settings->enabled_position_text_options)) $options=$settings->enabled_position_text_options;
        foreach($options as $value){
            if($value == 'left') $return['left']='Esquerdo';
            elseif($value == 'center') $return['center']='Centro';
            elseif($value == 'right') $return['right']='Direita';
        }
        return $return;
    }
    public function getPositionVerticalOptions(){
        $settings=Functions::getSettings();
        $return=[];
        $options=[];
        if(isset($settings->enabled_position_vertical_text_options)) $options=$settings->enabled_position_vertical_text_options;
        foreach($options as $value){
            if($value == 'top') $return['top']='Esquerdo';
            elseif($value == 'center') $return['center']='Centro';
            elseif($value == 'bottom') $return['bottom']='Direita';
        }
        return $return;
    }

}
