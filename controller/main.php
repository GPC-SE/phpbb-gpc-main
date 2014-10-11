<?php
/**
 *
 * @package phpBB Extension - GPC Main
 * @copyright (c) 2014 Robet Heim
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace gpc\main\controller;

class main
{
	/* @var \phpbb\config\config */
	protected $config;

	/* @var \phpbb\controller\helper */
	protected $helper;

	/* @var \phpbb\template\template */
	protected $template;

	/* @var \phpbb\user */
	protected $user;

	/* @var \phpbb\auth\auth */
	protected $auth;

	protected $request;

	protected $db;

	protected $phpEx;

	protected $phpbb_root_path;

	protected $table_prefix;

	protected $pagination;

	protected $tags_manager;

	/**
	 * Constructor
	 *
	 * @param \phpbb\config\config				$config
	 * @param \phpbb\controller\helper			$helper
	 * @param \phpbb\template\template			$template
	 * @param \phpbb\user						$user
	 * @param \phpbb\auth\auth					$auth
	 * @param \phpbb\request\request			$request
	 * @param \phpbb\db\driver\driver_interface	$db
	 * @param string							$php_ext	phpEx
	 * @param string							$root_path	phpBB root path
	 * @param string							$table_prefix
	 * @param \phpbb\pagination					$pagination
	 * @param \robertheim\topictags\service\tags_manager			$tags_manager
	 */
	public function __construct(\phpbb\config\config $config, \phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\user $user, \phpbb\auth\auth $auth, \phpbb\request\request $request, \phpbb\db\driver\driver_interface $db, $php_ext, $root_path, $table_prefix, $pagination, \robertheim\topictags\service\tags_manager $tags_manager)
	{
		$this->config = $config;
		$this->helper = $helper;
		$this->template = $template;
		$this->user = $user;
		$this->auth = $auth;
		$this->request = $request;
		$this->db = $db;
		$this->phpEx = $php_ext;
		$this->phpbb_root_path = $root_path;
		$this->table_prefix = $table_prefix;
		$this->pagination = $pagination;
		$this->tags_manager = $tags_manager;

		// always set the general template vars
		$this->set_general_template_vars();
	}

	/**
	 * Sets the general template vars which are used on (almost) every page.
	 */
	private function set_general_template_vars()
	{
		// here we know, that we are using a route of our controller and no phpBB route
		// if the current url contains "/community/" we redirect to the same adress without "/community"
		// because content /xyz should not be reachable at /community/xyz again

		if (!strpos($this->helper->get_current_url(), '/community/') === false)
		{
			redirect($this->remove_community($this->helper->get_current_url()), false, true);
			exit;
		}
//		FIXME helper->route might add ssid, so we cannot use it., remove gpc_main_controller2?
//		$home_link = $this->remove_community($this->helper->route('gpc_main_controller2')).'/';
		$home_link = '/';
		$this->template->assign_vars(array(
			'GPC_STYLE_PATH'	=> $home_link . 'community/ext/gpc/main/styles/prosilver/',
			'U_GPC_HOME'		=> $home_link,
			'U_GPC_IMPRESSUM'	=> $this->remove_community($this->helper->route('gpc_main_controller_impressum')),
			'U_GPC_VIDEOS'		=> $this->remove_community($this->helper->route('gpc_main_controller_videos')),
			'U_GPC_TUTORIALS'	=> $this->remove_community($this->helper->route('gpc_main_controller_tutorials')),
			'U_GPC_FAQS'		=> $this->remove_community($this->helper->route('gpc_main_controller_faqs')),
			'U_GPC_COMMUNITY'	=> 'community/index.php',
		));
	}

	private function remove_community($str)
	{
		return str_replace('community/', '', $str);
	}

	/**
	 * Controller for route / and /main
	 *
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 */
	public function show()
	{
		$this->template->assign_vars(array(
			'S_GPC_HOME_ACTIVE' => true,
		));
		return $this->helper->render('index.html');
	}

	/**
	 * Controller for route /disclaimer
	 *
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 */
	public function disclaimer()
	{
		return $this->helper->render('disclaimer.html');
	}

	/**
	 * Controller for route /impressum
	 *
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 */
	public function impressum()
	{
		return $this->helper->render('impressum.html');
	}

	/**
	 * Controller for route /videos
	 *
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 */
	public function videos()
	{
		$this->template->assign_vars(array(
			'S_GPC_VIDEOS_ACTIVE' => true,
		));
		return $this->helper->render('videos.html');
	}

	/**
	 * Controller for route /tutorials
	 *
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 */
	public function tutorials()
	{
		$this->template->assign_vars(array(
			'S_GPC_TUTORIALS_ACTIVE' => true,
		));

		$this->assign_tutorial_topics_block(array('basic'), 'basic');
		$this->assign_tutorial_topics_block(array('advanced'), 'advanced');
		$this->assign_tutorial_topics_block(array('expert'), 'expert');

		return $this->helper->render('tutorials.html');
	}


	private function assign_tutorial_topics_block($tags, $blockname)
	{
		global $phpbb_root_path, $phpEx;
		$start = 0;
		$limit = 10;
		$topics = $this->tags_manager->get_topics_by_tags($tags, $start, $limit);

		foreach ($topics as $topic)
		{
			$rating = rand(3, 5);

			$view_topic_url_params = 'f=' . $topic['forum_id'] . '&amp;t=' . $topic['topic_id'] ;
			$view_topic_url = append_sid("{$phpbb_root_path}viewtopic.$phpEx", $view_topic_url_params);

			$this->template->assign_block_vars($blockname, array(
				'TITLE'		=> $topic['topic_title'],
				'LINK'		=> $view_topic_url,
				'AUTHOR'	=> $topic['topic_first_poster_name'],
				'RATING'	=> $rating,
			));
		}
	}


	/**
	 * Controller for route /tutorial/view/{topic_id}
	 *
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 */
	public function tutorial_view($topic_id)
	{
		$sql = 'SELECT t.topic_title AS title, p.post_text AS text, p.bbcode_uid, p.bbcode_bitfield
	        FROM ' . TOPICS_TABLE . ' t, ' . POSTS_TABLE . ' p
	        WHERE t.topic_id = ' . (int) $topic_id . '
				AND t.topic_first_post_id = p.post_id';
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		$options = OPTION_FLAG_BBCODE + OPTION_FLAG_SMILIES + OPTION_FLAG_LINKS;

		$this->template->assign_vars(array(
		    'TITLE'		=> $row['title'],
		    'TEXT'		=> generate_text_for_display($row['text'], $row['bbcode_uid'], $row['bbcode_bitfield'], $options),
		));
		$this->template->assign_vars(array(
			'S_GPC_TUTORIALS_ACTIVE'	=> true,
		));
		return $this->helper->render('tutorial_view.html');
	}

	/**
	 * Controller for route /faqs
	 *
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 */
	public function faqs()
	{
		$this->template->assign_vars(array(
			'S_GPC_FAQS_ACTIVE' => true,
		));
		return $this->helper->render('faqs.html');
	}

	/**
	 * Controller for route /menu
	 *
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 */
	public function menu()
	{
		$this->template->assign_vars(array(
			'S_GPC_COMMUNITY_ACTIVE' => true,
		));
		return $this->helper->render('menu.html');
	}
}
