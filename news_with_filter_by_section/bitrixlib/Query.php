<?php
/**
 * Created by PhpStorm.
 * User: marsel
 * Date: 16.02.14
 * Time: 14:16
 */

namespace Bitrixlib;


class Query
{
	const FETCH_ONE    = 'one';
	const FETCH_ALL    = 'all';
	const FETCH_COLUMN = 'column';
	//	const FETCH_NEXT    = 'next';
	//	const FETCH_ELEMENT = 'element';

	public static function Query($sql, $fetch_type = 'all')
	{
		global $DB;

		$res = $DB->Query($sql);

		switch ($fetch_type)
		{
			case 'one':
				$data = self::FetchOne($res);
				break;
			case 'all':
				$data = self::FetchAll($res);
				break;
			case 'column':
				$data = self::FetchColumn($res);
			//			case 'next':
			//				$data = self::GetNext($res);
			//			case 'element':
			//				$data = self::GetNextElement($res);
			//				break;
		}

		return $data;
	}


	public static function GetNext($res)
	{
		$data = array();

		while ($row = $res->GetNext())
		{
			if (isset($row['ID']))
			{
				$data[$row['ID']] = $row;
			}
			else
			{
				$data[] = $row;
			}
		}

		return $data;
	}

	public static function GetNextElement($res, $get_properties)
	{
		$data = array();

		while ($row = $res->GetNextElement())
		{
			$elem = $row->GetFields();
			if ($get_properties)
			{
				$elem['props'] = $row->GetProperties();
			}
			$data[$elem['ID']] = $elem;
		}

		return $data;
	}

	public static function FetchOne($res)
	{
		return $res->Fetch();
	}

	public static function FetchAll($res)
	{
		$data = array();

		while ($row = $res->Fetch())
		{
			if (isset($row['ID']))
			{
				$data[$row['ID']] = $row;
			}
			else
			{
				$data[] = $row;
			}
		}

		return $data;
	}

	public static function FetchColumn($res)
	{
		$data = array();
		while ($row = $res->Fetch())
		{
			$data[] = array_shift($row);
		}

		return $data;

	}
} 