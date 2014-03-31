<?php
/**
 * User: marsel
 * Date: 16.02.14
 * Time: 11:56
 */

namespace Bitrixlib;

require_once(dirname(__FILE__) . '/Query.php');

class Medialibrary extends Query
{
	public static $per_page = array(25, 50, 100, 150);

	/**
	 * Альбомы по родителям
	 * @param int $parent_id
	 * @return array
	 */
	public static function AlbumsByParent($parent_id = 0, $with_photo = false)
	{
		$parent_id = (int)$parent_id;

		if ($with_photo)
		{
			$sql = "
			SELECT b_medialib_collection.* FROM b_medialib_collection
				WHERE b_medialib_collection.PARENT_ID =  {$parent_id}
				AND  EXISTS (
					SELECT * FROM b_medialib_collection_item
	                    WHERE b_medialib_collection_item.COLLECTION_ID = b_medialib_collection.ID
				)
		;";
		}
		else
		{
			$sql = "
			SELECT b_medialib_collection.* FROM
				b_medialib_collection
				WHERE PARENT_ID = {$parent_id}
		;";
		}
		return self::Query($sql);
	}

	/**
	 *
	 * @param array $album_ids
	 * @param int $deep
	 * @param int $page
	 * @param int $per_page
	 * @return array
	 */
	public static function PhotoByAlbums($album_ids = array(), $deep = 0, $page = 1, $per_page = 25)
	{
		if (!$album_ids || $page < 1 || !in_array($per_page, self::$per_page))
		{
			return array();
		}

		foreach ($album_ids as &$id)
		{
			$id = (int)$id;
		}

		// глубина вложенности
		if ($deep > 0)
		{
			//			$deep = (int)$deep;
			$album_ids_add = self::AlbumIDsByParents($album_ids);
			$album_ids     = array_merge($album_ids, $album_ids_add);
		}

		$album_ids = implode(',', $album_ids);


		$limit  = $per_page;
		$offset = $per_page * ($page - 1);


		$sql = "
		SELECT file.* FROM
			b_medialib_collection_item as collection
			LEFT JOIN  b_medialib_item as photo on collection.ITEM_ID = photo.ID
			LEFT JOIN  b_file as file on photo.SOURCE_ID = file.ID
			WHERE collection.COLLECTION_ID IN ({$album_ids})

			ORDER BY TIMESTAMP_X DESC
			LIMIT {$offset},{$limit}
		;";

		$result          = array();
		$result['items'] = self::Query($sql);

		$sql = "
		SELECT count(collection.ITEM_ID) as count FROM
			b_medialib_collection_item as collection
			WHERE collection.COLLECTION_ID IN ({$album_ids})
		;";

		$count              = self::Query($sql, self::FETCH_ONE);
		$count = $count['count'];
		$result['pages']    = ceil($count / $per_page);
		$result['page']     = $page;
		$result['photos_count']     =(int) $count;
		$result['per_page'] = $per_page;

		return $result;
	}

	/**
	 * фото по названию альбома
	 * @param int $parent_id
	 * @param array $album_name
	 * @param int $per_page
	 * @return array
	 */
	public static function PhotoByAlbumName($parent_id = 0,$album_name = array(),  $per_page = 25)
	{
		$limit  = $per_page;
		$album_name = htmlspecialchars($album_name,ENT_QUOTES);

		$sql = "
		SELECT file.* FROM
			b_medialib_collection_item as collection_item
			LEFT JOIN  b_medialib_collection as collection on collection_item.COLLECTION_ID = collection.ID
			LEFT JOIN  b_medialib_item as photo on collection_item.ITEM_ID = photo.ID
			LEFT JOIN  b_file as file on photo.SOURCE_ID = file.ID
			WHERE collection.NAME = '{$album_name}'
			AND collection.PARENT_ID = '{$parent_id}'

			ORDER BY TIMESTAMP_X DESC
			LIMIT {$limit}
		;";

		$result = self::Query($sql);

		return $result;
	}

	/**
	 * Массив id альбомов по id родителей
	 * @param array $parent_ids
	 * @return array
	 */
	public static function AlbumIDsByParents($parent_ids = array())
	{
		if (!$parent_ids)
		{
			return array();
		}

		foreach ($parent_ids as &$id)
		{
			$id = (int)$id;
		}
		$parent_ids = implode(',', $parent_ids);

		$sql = "
		SELECT ID as id FROM
			b_medialib_collection
			WHERE PARENT_ID IN ({$parent_ids})
		;";

		return self::Query($sql, self::FETCH_COLUMN);
	}

}
























