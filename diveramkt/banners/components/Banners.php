<?php namespace Diveramkt\Banners\Components;

use Cms\Classes\ComponentBase;

use Diveramkt\Banners\Models\Categorias;
use Diveramkt\Banners\Models\Banners as get_banners;
use Diveramkt\Banners\Models\Clicks;
use Request;
use Redirect;

class Banners extends ComponentBase
{

	public function componentDetails(){
		return [
			'name' => 'Banners',
			'description' => 'Banners are images with link and button'
		];
	}

	public function defineProperties(){
		return [
			'category' => [
				'title' => 'Categoria',
				'description' => 'Buscar banners pela categoria, id ou slug',
				'type' => 'dropdown',
			],
		];
	}

	public function onRun(){
		if($this->property('category')){
			if(is_numeric($this->property('category'))) $categoria=Categorias::where('id',$this->property('category'))->first();
			else $categoria=Categorias::where('slug',$this->property('category'))->first();
			if(isset($categoria->id)) $this->records=$categoria->banners;
		}else{
			$this->records=get_banners::enabled()->get();
		}
	}

	public function onClick(){
		$post=post();

		if(!isset($post['id'])) return;
		$id=$post['id'];

		$banner=get_banners::enabled()->where('id',$id)->first();
		// if(isset($post['url']) && $banner->url != $post['url']) return;
		// if(isset($post['banner']) && $banner->banner != $post['banner']) return;

		$click=new Clicks();
		$click->date_create=date('y-m-d');
		$click->url=Request::url('/');
		$click->banners_id=$id;
		$click->save();
		
		if(!isset($post['noredirect']) && !empty($banner->url)) return Redirect::To($banner->url);
	}

	public function getCategoryOptions(){
		$return=[];
		$categorias=Categorias::get();
		if(isset($categorias[0]->id)){
			foreach ($categorias as $key => $value) {
				$return[$value->slug]=$value->title;
			}
		}
		return $return;
	}

	// protected function getBanner(){
	// 	if ($this->property('banner') == "") {
	// 		return Banner::first();
	// 	}else{
	// 		return Banner::where('id',$this->property('banner'))->first();
	// 	}

	// }

	// protected function getAllBanner(){
	// 	$query = Banner::all();

	// 	foreach ($query as $id=>$c)
	//         $result[$c->id] = $c->title;

	//     return $result;
	// }

	// public function createSettingsFields(){
	// 	$defaultFields = Settings::instance()->toArray();

	// 	if (!empty($defaultFields)) {
 //            foreach ($defaultFields['value'] as $key => $defaultValue) {
 //                $this->settings[$key] = $defaultValue;
 //            }
 //        }

 //        $this->settings = (object)$this->settings;
	// }

	public $records;
}