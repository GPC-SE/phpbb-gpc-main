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
use robertheim\videos\PREFIXES;

/**
* Manager for the videos section of the GPC website.
*/
class gpc_videos_manager
{

	private $db;
	private $config;
	private $auth;
	private $table_prefix;

	public function __construct(
					\phpbb\db\driver\driver_interface $db,
					\phpbb\config\config $config,
					\phpbb\auth\auth $auth,
					$table_prefix)
	{
		$this->db			= $db;
		$this->config		= $config;
		$this->auth			= $auth;
		$this->table_prefix	= $table_prefix;
	}
	
	public function get_topics_with_video()
	{
		$video_url_field = $this->get_video_url_field();
		$sql_array = array(
			'SELECT'	=>  '*',
			'FROM'		=> array(
				TOPICS_TABLE => 't',
			),
			'WHERE'		=> "t.$video_url_field<>''",
		);
		$sql = $this->db->sql_build_query('SELECT_DISTINCT', $sql_array);
		$result = $this->db->sql_query_limit($sql, 10);
		$topics = array();
		while ($row = $this->db->sql_fetchrow($result))
		{
			$topics[] = $row;
		}
		$this->db->sql_freeresult($result);
		return $topics;
	}
	
	public function get_video_url_field()
	{
		return PREFIXES::CONFIG . '_url';
	}
}

