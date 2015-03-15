<?php
/**
*
* @package phpBB Extension - GPC main
* @copyright (c) 2014 Robet Heim
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace gpc\main\service;

/**
 * @ignore
 */
use robertheim\videos\tables as RH_VIDEOS_TABLES;

/**
* Manager for the videos section of the GPC website.
*/
class gpc_videos_manager
{

	private $db;
	private $config;
	private $auth;
	private $table_prefix;
	private $videos_manager;

	public function __construct(
					\phpbb\db\driver\driver_interface $db,
					\phpbb\config\config $config,
					\phpbb\auth\auth $auth,
					\robertheim\videos\service\videos_manager $videos_manager,
					$table_prefix)
	{
		$this->db				= $db;
		$this->config			= $config;
		$this->auth				= $auth;
		$this->videos_manager	= $videos_manager;
		$this->table_prefix		= $table_prefix;
	}

	/**
	 * @return int count of all videos
	 */
	public function count_total_videos()
	{
		$sql_array = array(
			'SELECT'	=>  'COUNT(*) as count',
			'FROM'		=> array(
				$this->table_prefix . RH_VIDEOS_TABLES::VIDEOS => 'v',
				TOPICS_TABLE => 't',
				FORUMS_TABLE => 'f',
			),
			'WHERE'		=> 't.topic_id = v.topic_id
				AND f.forum_id = t.forum_id
				AND f.rh_videos_enabled',
		);
		$sql = $this->db->sql_build_query('SELECT', $sql_array);
		$result = $this->db->sql_query($sql);
		$count = $this->db->sql_fetchfield('count');
		$this->db->sql_freeresult($result);
		return (int) $count;
	}

	/**
	 * @param $start start of videos
	 * @param $limit max count of videos to return
	 * @return array mapping topic_id => topic-row + field 'rh_video'
	 */
	public function get_topics_with_video($start, $limit)
	{
		$sql_array = array(
			'SELECT'	=>  't.*',
			'FROM'		=> array(
				$this->table_prefix . RH_VIDEOS_TABLES::VIDEOS => 'v',
				TOPICS_TABLE => 't',
				FORUMS_TABLE => 'f',
			),
			'WHERE'		=> 't.topic_id = v.topic_id
				AND f.forum_id = t.forum_id
				AND f.rh_videos_enabled',
		);
		$sql = $this->db->sql_build_query('SELECT_DISTINCT', $sql_array);
		$result = $this->db->sql_query_limit($sql, $limit, $start);
		$topics = array();
		$topic_ids = array();
		while ($row = $this->db->sql_fetchrow($result))
		{
			$topic_id = (int) $row['topic_id'];
			$topics[$topic_id] = $row;
			$topic_ids[] = $topic_id;
		}
		$this->db->sql_freeresult($result);

		$videos = $this->videos_manager->get_videos_for_topic_ids($topic_ids);
		foreach ($videos as $video)
		{
			$topics[$video['topic_id']]['rh_video'] = $video['video'];
		}
		return $topics;
	}
}
