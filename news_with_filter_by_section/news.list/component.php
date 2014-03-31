<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
global $DB;
/** @global CUser $USER */
global $USER;
/** @global CMain $APPLICATION */
global $APPLICATION;

if (!isset($arParams["CACHE_TIME"]))
{
	$arParams["CACHE_TIME"] = 3600;
}

$arParams["IBLOCK_TYPE"] = trim($arParams["IBLOCK_TYPE"]);
if (strlen($arParams["IBLOCK_TYPE"]) <= 0)
{
	$arParams["IBLOCK_TYPE"] = "news";
}
if ($arParams["IBLOCK_TYPE"] == "-")
{
	$arParams["IBLOCK_TYPE"] = "";
}

if (!is_array($arParams["IBLOCKS"]))
{
	$arParams["IBLOCKS"] = array($arParams["IBLOCKS"]);
}
foreach ($arParams["IBLOCKS"] as $k => $v)
{
	if (!$v)
	{
		unset($arParams["IBLOCKS"][$k]);
	}
}

if (!is_array($arParams["FIELD_CODE"]))
{
	$arParams["FIELD_CODE"] = array();
}
foreach ($arParams["FIELD_CODE"] as $key => $val)
{
	if (!$val)
	{
		unset($arParams["FIELD_CODE"][$key]);
	}
}

$arParams["SORT_BY1"] = trim($arParams["SORT_BY1"]);
if (strlen($arParams["SORT_BY1"]) <= 0)
{
	$arParams["SORT_BY1"] = "ACTIVE_FROM";
}
if (!preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $arParams["SORT_ORDER1"]))
{
	$arParams["SORT_ORDER1"] = "DESC";
}

if (strlen($arParams["SORT_BY2"]) <= 0)
{
	$arParams["SORT_BY2"] = "SORT";
}
if (!preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $arParams["SORT_ORDER2"]))
{
	$arParams["SORT_ORDER2"] = "ASC";
}

$arParams["NEWS_COUNT"] = intval($arParams["NEWS_COUNT"]);
if ($arParams["NEWS_COUNT"] <= 0)
{
	$arParams["NEWS_COUNT"] = 20;
}

$arParams["DETAIL_URL"] = trim($arParams["DETAIL_URL"]);

$arParams["ACTIVE_DATE_FORMAT"] = trim($arParams["ACTIVE_DATE_FORMAT"]);
if (strlen($arParams["ACTIVE_DATE_FORMAT"]) <= 0)
{
	$arParams["ACTIVE_DATE_FORMAT"] = $DB->DateFormatToPHP(CSite::GetDateFormat("SHORT"));
}


//$arResult = array();
$cur_page = $APPLICATION->GetCurPage();

$cache_keys = $_GET;
unset($cache_keys['clear_cache']);
$cache_keys             = $cur_page . http_build_query($cache_keys);
$arResult['cache_keys'] = $cache_keys;

global $COMPLEX_VARIABLES;
//print_r($COMPLEX_VARIABLES);
//exit;
//$COMPLEX_VARIABLES['VARIABLES'];


if ($this->StartResultCache(false, $cache_keys))
{
	//SQl>


	/**
	 * Новости
	 */
	$arFilter = array("IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
					  "IBLOCK_ID"   => $arParams["IBLOCK_ID"],
					  "ACTIVE"      => "Y",
	);

	if (isset($COMPLEX_VARIABLES['VARIABLES']) && $COMPLEX_VARIABLES['VARIABLES'])
	{
		$arFilter = array_merge($arFilter, $COMPLEX_VARIABLES['VARIABLES']);
	}

	//	print_r($arFilter);
	//	exit;

	$order          = array($arParams["SORT_BY1"] => $arParams["SORT_ORDER1"], $arParams["SORT_BY2"] => $arParams["SORT_ORDER2"]);
	$arSelect       = array();
	$NavStartParams = array(
		'nPageSize' => 2
	);
	$get_nav_string = 1;

	$data     = \Bitrixlib\GetList::Element($arFilter, 0, $arSelect, $order, $NavStartParams, $get_nav_string);
	$arResult = $data;

	//	$items  = $arr['items'];
	//	$nav_string = $arr['nav_string'];

	//	print_r($elements);
	//	var_dump($elements['nav_string']);
	//	exit;

	/**
	 * Категории
	 */

	$arFilter = array("IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
					  "IBLOCK_ID"   => $arParams["IBLOCK_ID"],
					  "ACTIVE"      => "Y",
	);

	$order                = array('SORT' => 'ASC');
	$sections             = \Bitrixlib\GetList::Section($arFilter, $arSelect, $order);
	$arResult['sections'] = $sections;

	/**
	 * Текущая категория
	 */

	$arFilter = array("IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
					  "IBLOCK_ID"   => $arParams["IBLOCK_ID"],
					  "ACTIVE"      => "Y",
	);
	//	if (isset($COMPLEX_VARIABLES['VARIABLES']) && $COMPLEX_VARIABLES['VARIABLES'])
	//	{


	$section_filter = array();
	if (isset($COMPLEX_VARIABLES['VARIABLES']['SECTION_ID']))
	{
		$section_filter['ID'] = $COMPLEX_VARIABLES['VARIABLES']['SECTION_ID'];
	}
	if (isset($COMPLEX_VARIABLES['VARIABLES']['SECTION_CODE']))
	{
		$section_filter['CODE'] = $COMPLEX_VARIABLES['VARIABLES']['CODE'];
	}

	$arResult['current_section_id'] = null;

	if ($section_filter)
	{
		$arFilter = array_merge($arFilter, $section_filter);

		$order                       = array('SORT' => 'ASC');
		$section                     = \Bitrixlib\GetList::SectionOne($arFilter, $arSelect);
		$arResult['current_section'] = $section;

		if ($arResult['current_section'])
		{
			$arResult['current_section_id'] = $arResult['current_section']['ID'];
		}

		//		var_dump($section);

		//раздела не существует
		if (!$section)
		{
			$this->AbortResultCache();
			@define("ERROR_404", "Y");
		}
	}


	//	print_r($arFilter);
	//	print_r($arResult['current_section']);
	//	exit;


	//<SQL


	$this->IncludeComponentTemplate();
}
