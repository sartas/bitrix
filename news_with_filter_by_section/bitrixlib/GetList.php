<?php
/**
 * Created by PhpStorm.
 * User: marsel
 * Date: 16.02.14
 * Time: 16:11
 */

namespace Bitrixlib;

use CIBlockSection;
use CIBlockElement;
use CModule;
use CUser;

require_once(dirname(__FILE__) . '/Query.php');


if (!CModule::IncludeModule("iblock"))
{
	$this->AbortResultCache();
	ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
	return;
}

class GetList extends Query
{
	public static function Section($filter = array(), $select = array(), $order = array('SORT' => 'ASC'))
	{
//		print_r($filter);
//		print_r($select);
//		exit;
		$bIncCnt        = false;
		$NavStartParams = false;

		$res = CIBlockSection::GetList($order, $filter, $bIncCnt, $select, $NavStartParams);

		return Query::GetNext($res);
	}

	public static function SectionOne($filter = array(), $select = array())
	{
//		print_r($filter);
//		print_r($select);
//		exit;
		$data = GetList::Section($filter, $select);

		if (is_array($data))
		{
			return array_shift($data);
		}
		else
		{
			return false;
		}
	}

	public static function Element($filter = array(), $get_properties = false, $select = array(), $order = array('SORT' => 'ASC'), $NavStartParams = false, $get_nav_string = false)
	{
		$bIncCnt = false;

		if (!isset($_GET['page']) && isset($_GET['PAGEN_1']))
		{
			$_GET['page'] = $_GET['PAGEN_1'];
		}

		if (!isset($_GET['page']))
		{
			$_GET['page'] = 1;
		}

		if ($get_nav_string)
		{
			$NavStartParams['iNumPage'] = (int)$_GET['page'];
		}

		$res = CIBlockElement::GetList($order, $filter, $bIncCnt, $NavStartParams, $select);

		$items = Query::GetNextElement($res, $get_properties);

		if ($get_nav_string)
		{
			$nav_string = $res->GetPageNavStringEx($navComponentObject, false, false, false);

			return array('items' => $items, 'nav_string' => $nav_string);
		}
		else
		{
			return $items;
		}
	}

	/**
	 * Список пользователей
	 * @param array $filter
	 * @param array $select_uf
	 * @param array $order
	 */
	public static function User($filter = array(), $select_uf = array(), $order = array())
	{
		$res = CUser::GetList(($by = "ID"), ($order = "desc"), $filter, array("SELECT" => $select_uf));
		return Query::FetchAll($res);
	}

	public static function UserOne($filter = array(), $select_uf = array(), $order = array())
	{
		$res = CUser::GetList(($by = "ID"), ($order = "desc"), $filter, array("SELECT" => $select_uf));
		return Query::FetchOne($res);
	}

}