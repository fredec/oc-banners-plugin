<?php namespace Diveramkt\Banners\Components;

use Cms\Classes\ComponentBase;

use Diveramkt\Banners\Classes\Functions;
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
			'resize_width' => [
				'title'             => 'Largura da imagem (resize)',
				'type'              => 'string',
				'validationPattern' => '^[0-9]+$',
				'validationMessage' => 'Apenas número permitido',
				'description' => 'Necessário ter o filter resize',
				'default'           => '0',
				'group'           => 'Resize',
			],
			'resize_height' => [
				'title'             => 'Altura da imagem (resize)',
				'type'              => 'string',
				'validationPattern' => '^[0-9]+$',
				'validationMessage' => 'Apenas número permitido',
				'description' => 'Necessário ter o filter resize',
				'default'           => '0',
				'group'           => 'Resize',
			],
			'resize_crop' => [
				'title'             => 'Mode do resize crop',
				'type'              => 'checkbox',
				'default'           => '0',
				'group'           => 'Resize',
			],
			'resize_width_tablet' => [
				'title'             => 'Largura da imagem (resize)',
				'type'              => 'string',
				'validationPattern' => '^[0-9]+$',
				'validationMessage' => 'Apenas número permitido',
				'description' => 'Necessário ter o filter resize',
				'default'           => '0',
				'group'           => 'Resize no tablet',
			],
			'resize_height_tablet' => [
				'title'             => 'Altura da imagem (resize)',
				'type'              => 'string',
				'validationPattern' => '^[0-9]+$',
				'validationMessage' => 'Apenas número permitido',
				'description' => 'Necessário ter o filter resize',
				'default'           => '0',
				'group'           => 'Resize no tablet',
			],
			'resize_crop_tablet' => [
				'title'             => 'Mode do resize crop',
				'type'              => 'checkbox',
				'default'           => '0',
				'group'           => 'Resize no tablet',
			],
			'resize_width_mobile' => [
				'title'             => 'Largura da imagem (resize)',
				'type'              => 'string',
				'validationPattern' => '^[0-9]+$',
				'validationMessage' => 'Apenas número permitido',
				'description' => 'Necessário ter o filter resize',
				'default'           => '0',
				'group'           => 'Resize no mobile',
			],
			'resize_height_mobile' => [
				'title'             => 'Altura da imagem (resize)',
				'type'              => 'string',
				'validationPattern' => '^[0-9]+$',
				'validationMessage' => 'Apenas número permitido',
				'description' => 'Necessário ter o filter resize',
				'default'           => '0',
				'group'           => 'Resize no mobile',
			],
			'resize_crop_mobile' => [
				'title'             => 'Mode do resize crop',
				'type'              => 'checkbox',
				'default'           => '0',
				'group'           => 'Resize no mobile',
			],
			// 'position' => [
			// 	'title'             => 'Posição',
			// 	'type'              => 'string',
			// 	'validationPattern' => '^[0-9]+$',
			// 	'validationMessage' => 'Apenas número permitido',
			// 	'description' => 'O número da posição do banner na ordem',
			// 	'default'           => '0',
			// 	'group'           => 'Banner por posição',
			// ],
			// 'position_itens' => [
			// 	'title'             => 'Mode do resize crop',
			// 	'type'              => 'checkbox',
			// 	'default'           => '0',
			// 	'group'           => 'Banner por posição',
			// ],
			'js_manual' => [
				'title' => 'JS Manual',
				'description' => 'Adicionar o js manual, variável: addFilesJs',
				'default' => false,
				'type' => 'checkbox',
			],
		];
	}

	public $addFilesJs=[];
	public function onRun(){
		$settings=Functions::getSettings();
		$this->addFilesJs=['/plugins/diveramkt/banners/assets/js/scripts.js'];
		if(!$this->property('js_manual')) $this->addJs(url($this->addFilesJs[0]),['defer' => true]);
		if($this->property('category')){
			if(is_numeric($this->property('category'))) $categoria=Categorias::where('id',$this->property('category'))->first();
			else $categoria=Categorias::where('slug',$this->property('category'))->first();
			if(isset($categoria->id)) $records=$categoria->banners;
		}else{
			// $records=get_banners::enabled()->get();
			return;
		}

		$height=preg_replace("/[^0-9]/", "", $this->property('resize_height')); if(!$height) $height='auto';
		$width=preg_replace("/[^0-9]/", "", $this->property('resize_width')); if(!$width) $width='auto';
		$mode='auto';
		if($this->property('resize_height') || $this->property('resize_width')){
			if($this->property('resize_crop')) $mode='crop';
			$this->resize=[
				'height' => $height,
				'width' => $width,
				'mode' => $mode,
			];
		}

		$height_tablet=preg_replace("/[^0-9]/", "", $this->property('resize_height_tablet')); if(!$height_tablet) $height_tablet='auto';
		$width_tablet=preg_replace("/[^0-9]/", "", $this->property('resize_width_tablet')); if(!$width_tablet) $width_tablet='auto';
		$mode_tablet='auto';
		if($this->property('resize_height_tablet') || $this->property('resize_width_tablet')){
			if($this->property('resize_crop_tablet')) $mode_tablet='crop';
			$this->resize_tablet=[
				'height' => $height_tablet,
				'width' => $width_tablet,
				'mode' => $mode_tablet,
			];
		}

		$height_mobile=preg_replace("/[^0-9]/", "", $this->property('resize_height_mobile')); if(!$height_mobile) $height_mobile='auto';
		$width_mobile=preg_replace("/[^0-9]/", "", $this->property('resize_width_mobile')); if(!$width_mobile) $width_mobile='auto';
		$mode_mobile='auto';
		if($this->property('resize_height_mobile') || $this->property('resize_width_mobile')){
			if($this->property('resize_crop_mobile')) $mode_mobile='crop';
			$this->resize_mobile=[
				'height' => $height_mobile,
				'width' => $width_mobile,
				'mode' => $mode_mobile,
			];
		}

		$records->each(function($record) use ($settings) {
			$image=false;
			if($this->resize){
				if(class_exists('\Diveramkt\Uploads\Classes\Image')) $image = new \Diveramkt\Uploads\Classes\Image($record->banner);
				elseif(class_exists('\ToughDeveloper\ImageResizer\Classes\Image')) $image = new \ToughDeveloper\ImageResizer\Classes\Image($record->banner);
			}
			if($image){
				$record->banner_resized=$image->resize($this->resize['width'], $this->resize['height'], ['mode' => $this->resize['mode'], 'quality' => 80]);
			}else $record->banner_resized=url($record->banner);

			$image=str_replace([url('/').'/',url('/')], ['',''], $record->banner_resized);
			if(file_exists($image)){
				$size=getimagesize($image);
				if(isset($size[0])) $record->banner_resized_width=$size[0];
				if(isset($size[1])) $record->banner_resized_height=$size[1];
			}
			if(!isset($record->banner_resized_width) && isset($this->resize['width'])) $record->banner_resized_width=$this->resize['width'];
			if(!isset($record->banner_resized_height) && isset($this->resize['height'])) $record->banner_resized_height=$this->resize['height'];

			if($settings->enabled_image_tablet){
			// ///////////////TABLET
				if(!$record->banner_tablet || !file_exists(str_replace('/storage', 'storage', $record->banner_tablet))){
					$record->banner_tablet=$record->banner;
					$banner_tablet=$record->banner;
				}else $banner_tablet=$record->banner_tablet;
				if(!empty($banner_tablet)){
					$image_tablet=false;
					if($this->resize_tablet){
						if(class_exists('\Diveramkt\Uploads\Classes\Image')) $image_tablet = new \Diveramkt\Uploads\Classes\Image($banner_tablet);
						elseif(class_exists('\ToughDeveloper\ImageResizer\Classes\Image')) $image_tablet = new \ToughDeveloper\ImageResizer\Classes\Image($banner_tablet);
					}
					if($image_tablet){
						$record->banner_tablet_resized=$image_tablet->resize($this->resize_tablet['width'], $this->resize_tablet['height'], ['mode' => $this->resize_tablet['mode'], 'quality' => 80]);
					}else $record->banner_tablet_resized=url($banner_tablet);

					$image=str_replace([url('/').'/',url('/')], ['',''], $record->banner_tablet_resized);
					if(file_exists($image)){
						$size=getimagesize($record->banner_tablet_resized);
						if(isset($size[0])) $record->banner_tablet_resized_width=$size[0];
						if(isset($size[1])) $record->banner_tablet_resized_height=$size[1];
					}
				}
				if(!isset($record->banner_tablet_resized_width) && isset($this->resize_tablet['width'])) $record->banner_tablet_resized_width=$this->resize_tablet['width'];
				if(!isset($record->banner_tablet_resized_height) && isset($this->resize_tablet['height'])) $record->banner_tablet_resized_height=$this->resize_tablet['height'];
			// ///////////////TABLET
			}

			if($settings->enabled_image_mobile){
			// ///////////////MOBILE
				// if(!$record->banner_mobile || !file_exists(str_replace('/storage', 'storage', $record->banner_mobile))){
				// 	$record->banner_mobile=$record->banner;
				// 	$banner_mobile=$record->banner;
				// }else $banner_mobile=$record->banner_mobile;

				if(!$record->banner_mobile){
					$record->banner_mobile=$record->banner;
					$banner_mobile=$record->banner;
				}else $banner_mobile=$record->banner_mobile;
				
				if(!empty($banner_mobile)){
					$image_mobile=false;
					if($this->resize_mobile){
						if(class_exists('\Diveramkt\Uploads\Classes\Image')) $image_mobile = new \Diveramkt\Uploads\Classes\Image($banner_mobile);
						elseif(class_exists('\ToughDeveloper\ImageResizer\Classes\Image')) $image_mobile = new \ToughDeveloper\ImageResizer\Classes\Image($banner_mobile);
					}
					if($image_mobile){
						$record->banner_mobile_resized=$image_mobile->resize($this->resize_mobile['width'], $this->resize_mobile['height'], ['mode' => $this->resize_mobile['mode'], 'quality' => 80]);

					}else $record->banner_mobile_resized=url($banner_mobile);

					$image=str_replace([url('/').'/',url('/')], ['',''], $record->banner_mobile_resized);
					if(file_exists($record->banner_mobile_resized)){
						$size=getimagesize($record->banner_mobile_resized);
						if(isset($size[0])) $record->banner_mobile_resized_width=$size[0];
						if(isset($size[1])) $record->banner_mobile_resized_height=$size[1];
					}
				}
				if(!isset($record->banner_mobile_resized_width) && isset($this->resize_mobile['width'])) $record->banner_mobile_resized_width=$this->resize_mobile['width'];
				if(!isset($record->banner_mobile_resized_height) && isset($this->resize_mobile['height'])) $record->banner_mobile_resized_height=$this->resize_mobile['height'];
			// ///////////////MOBILE
			}
		});

$this->records=$records;
}

	// public function registerMarkupTags()
	// {
	// 	return [];
	// 	return [
	// 		'filters' => [
	// 			'resize' => function($file_path) {
	// 				return url($file_path);
	// 			},
	// 		]
	// 	];
	// }

public function onBannersAddClick(){
	$this->onClick();
}

public function onClick(){
	$post=post();

	if(!isset($post['id'])) return;
	$id=$post['id'];

	$banner=get_banners::enabled()->where('id',$id)->first();
		// if(isset($post['url']) && $banner->url != $post['url']) return;
		// if(isset($post['banner']) && $banner->banner != $post['banner']) return;
	if(!isset($banner->id)) return;

	$click=new Clicks();
	$click->date_create=date('y-m-d');
	$click->url=Request::url('/');
	$click->banners_id=$id;
	$click->save();

	if(!post('noredirect') && !empty($banner->url)) return Redirect::To($banner->url);
}

public function getCategoryOptions(){
	$return=[];
	$categorias=Categorias::get();
	if(isset($categorias[0]->id)){
		$return[]='Selecionar';
		foreach ($categorias as $key => $value) {
			$return[$value->slug]=$value->title;
		}
	}
	return $return;
}

public function onBannerPositionItem($item=0, $porVez=0){
	if(!$porVez) return;
	if($item%$porVez == 0) return $this->onBannerPosition(($item/$porVez)-1);
}
public function onBannerPosition($posicao=0){
	$pos=0;
	if(!$this->records) $this->onRun();
	if(isset($this->records[0]->id)) $pos=$posicao%count($this->records);
	if(isset($this->records[$pos])) return $this->records[$pos];
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

public $records, $resize=false, $resize_mobile=false, $resize_tablet=false;
}