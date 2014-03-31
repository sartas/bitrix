<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
} ?>

<?
/**
 * наши работы
 */

//$arResult['works'] = array();
//


//print_r($arResult['NAME']);
include_once $_SERVER['DOCUMENT_ROOT'].'/bitrixlib/Medialibrary.php';

$album_name = $arResult['NAME'];
$parent_id = 4;
$photos = Bitrixlib\Medialibrary::PhotoByAlbumName($parent_id,$album_name);

$arResult['works'] = array();

if ($photos)
{
	foreach ($photos as $key => $img_arr)
	{
		$arResult['works'][$key]          = array();
		$arResult['works'][$key]['small'] = CFile::ResizeImageGet(
			$key, array("width" => 287, "height" => 202), BX_RESIZE_IMAGE_EXACT, true
		);
		$arResult['works'][$key]['big']   = CFile::ResizeImageGet(
			$key, array("width" => 1000, "height" => 750), BX_RESIZE_IMAGE_PROPORTIONAL, true
		);
	}
}
//print_r($arResult['works']);
//exit;



//варианты с текстурами;
$arResult['variants'] = array();

if (isset($arResult['DISPLAY_PROPERTIES']['variants']) && $arResult['DISPLAY_PROPERTIES']['variants']['VALUE'])
{
	$variant_iblock_id = $arResult['PROPERTIES']['variants']['LINK_IBLOCK_ID'];
	$variant_ids       = $arResult['DISPLAY_PROPERTIES']['variants']['VALUE'];
	//	$variant_ids = implode(',',$variant_ids);

	include_once($_SERVER['DOCUMENT_ROOT'] . '/bitrixlib/GetList.php');

	$filter = array('IBLOCK_ID' => $variant_iblock_id, 'ID' => $variant_ids);

	$variants = Bitrixlib\GetList::Element($filter, 1);

	foreach ($variants as &$variant)
	{
		$variant['price'] = $variant['props']['price']['VALUE'];

		$variant['pic'] = CFile::ResizeImageGet(
			$variant['PREVIEW_PICTURE'], array("width" => 1000, "height" => 750), BX_RESIZE_IMAGE_PROPORTIONAL, false
		);

		$declinations            = array();
		$declinations[]          = $variant['props']['name_1']['VALUE'];
		$declinations[]          = $variant['props']['name_2']['VALUE'];
		$declinations[]          = $variant['props']['name_3']['VALUE'];
		$variant['declinations'] = $declinations;

		//		print_r($variants);
		//		exit;

		$variant['textures'] = array();
		$textures            = $variant['props']['textures'];

		//		$variant['props']['textures']['VALUE'];
		//		$variant['props']['textures']['DESCRIPTION'];

		foreach ($textures['VALUE'] as $key => $pic_id)
		{
			$variant['textures'][$pic_id]                = array();
			$variant['textures'][$pic_id]['description'] = $textures['DESCRIPTION'][$key];
			//			$variant['textures'][$pic_id]['pic']         = CFile::GetByID(
			//				$pic_id
			//			);
			$variant['textures'][$pic_id]['pic'] = CFile::ResizeImageGet(
				$pic_id, array("width" => 283, "height" => 190), BX_RESIZE_IMAGE_EXACT, true
			);
		}


	}

	//print_r($variants);
	//	exit;
	$arResult['variants'] = $variants;

	//	print_R($arResult['DISPLAY_PROPERTIES']['variants']);
	//	print_R($filter);
	//	print_R($arResult);
	//	exit;
}
