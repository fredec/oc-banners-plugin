<?php namespace Diveramkt\Banners\Models;

use Model;

/**
 * Model
 */
class Categorias extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    public $jsonable = ['infos'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'diveramkt_banners_categorias';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'title' => 'required',
    ];

    public $belongsToMany = [
        'banners' => [
            'table' => 'diveramkt_banners_banners_categorias',
            'Diveramkt\Banners\Models\Banners',
            'scope' => 'enabled',
        ],
    ];

    public function beforeSave($model=false){
        
        if(!$this->slug || empty($this->slug)){
            $this->slug=\Str::slug($this->title);
        }
        $stop=1;
        for ($i=0; $i < $stop; $i++) {
            $slug=$this->slug;
            if($i) $slug.='-'.$i;
            $veri=Self::where('slug',$slug);
            if(isset($this->id)) $veri=$veri->where('id','!=',$this->id);
            $veri=$veri->first();
            if(isset($veri->id)) $stop++;
        }
        $this->slug=$slug;

        $infos=$this->infos;
        foreach ($this->attributes as $key => $value) {
            if(!\Schema::hasColumn($this->table, $key)){
                $infos[$key]=$value;
                unset($this->$key);
            }
        }
        $this->infos=$infos;

    }

    public function getDescriptionAttribute(){
        return $this->infos['description'];
    }

    // public function scopeEnabled($query){
    //     return $query->where('')
    // }

}
