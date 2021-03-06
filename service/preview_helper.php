<?php
/**
*
* @package phpBB Extension - GPC main
* @copyright (c) 2014 Robet Heim
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace gpc\main\service;

use \gpc\main\util\phpbbutils;

/**
* Previewing posts requires several steps which are bundled here.
*/
class preview_helper
{
	protected $db;
	protected $tags_manager;
	protected $phpbb_root_path;
	protected $php_ext;

	public function __construct(
		\phpbb\db\driver\driver_interface $db,
		\robertheim\topictags\service\tags_manager $tags_manager,
		$phpbb_root_path,
		$php_ext
	)
	{
		$this->db = $db;
		$this->tags_manager = $tags_manager;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
	}

	/**
	 * Creates previews of given topics
	 *
	 * @param array $topics holding all topic information of the topics to preview
	 * @return array with title, url, preview_text and first_poster_name
	 */
	public function preview_topics(array $topics, $max_preview_text_length = 100)
	{
		$preview_topics = array();
		foreach ($topics as $topic)
		{
			$view_topic_url_params = 'f=' . $topic['forum_id'] . '&amp;t=' . $topic['topic_id'] ;
			$view_topic_url = append_sid("{$this->phpbb_root_path}viewtopic.{$this->php_ext}", $view_topic_url_params);

			// get text of first post
			$sql = 'SELECT p.post_text AS text, p.bbcode_uid, p.bbcode_bitfield
				FROM ' . TOPICS_TABLE . ' t, ' . POSTS_TABLE . ' p
				WHERE t.topic_id = ' . (int) $topic['topic_id'] . '
					AND t.topic_first_post_id = p.post_id';
			$result = $this->db->sql_query($sql);
			$row = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);

			$text = phpbbutils::make_preview_text_from_post($row['text'], $row['bbcode_uid'], $row['bbcode_bitfield'], $max_preview_text_length);

			$preview_topics[] = array(
				'title'				=> $topic['topic_title'],
				'url'				=> $view_topic_url,
				'preview_text'		=> $text,
				'first_poster_name'	=> $topic['topic_first_poster_name'],
			);
		}
		return $preview_topics;
	}

	/**
	 *
	 * @param array $tags
	 * @param unknown $max_topics
	 * @param number $max_preview_text_length
	 * @param string $order_by valid arguments: topic_last_post_time, topic_time
	 */
	public function preview_topics_by_tags(array $tags, $max_topics, $max_preview_text_length = 100, $order_by = 'topic_last_post_time')
	{
		$sql = $this->tags_manager->get_topics_build_query($tags);

		if ('topic_last_post_time' == $order_by) {
			$order_by = ' ORDER BY topics.topic_last_post_time DESC';
			$sql .= $order_by;
		} else if ('topic_time' == $order_by) {
			$order_by = ' ORDER BY topics.topic_time DESC';
			$sql .= $order_by;
		}

		$start = 0;
		$result = $this->db->sql_query_limit($sql, $max_topics, $start);
		$topics = array();
		while ($row = $this->db->sql_fetchrow($result))
		{
			$topics[] = $row;
		}
		$this->db->sql_freeresult($result);
		return $this->preview_topics($topics, $max_preview_text_length);
	}

}
