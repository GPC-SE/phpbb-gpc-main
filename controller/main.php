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

	public function __construct(
		\phpbb\config\config $config,
		\phpbb\controller\helper $helper,
		\phpbb\template\template $template,
		\phpbb\user $user,
		\phpbb\auth\auth $auth,
		\phpbb\request\request $request,
		\phpbb\db\driver\driver_interface $db,
		$php_ext,
		$root_path,
		$table_prefix,
		$pagination,
		\robertheim\topictags\service\tags_manager $tags_manager
	)
	{
		$this->helper = $helper;

		// if current url is the board-index "/community/" we redirect to community/index.php
		if (preg_match('/\/community\/$/i', $this->helper->get_current_url()))
		{
			redirect($this->helper->get_current_url() . 'index.php', false, true);
			exit;
		}

		// here we know, that we are using a route of our controller and no phpBB route
		// if the current url contains "/community/" we redirect to the same adress without "/community"
		// because content /xyz should not be reachable at /community/xyz again
		if (!strpos($this->helper->get_current_url(), '/community/') === false)
		{
			redirect($this->remove_community($this->helper->get_current_url()), false, true);
			exit;
		}

		$this->config = $config;
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
				
			//$tutorial_url = $this->remove_community($this->helper->route('gpc_main_controller_tutorial_view', array('topic_id' => $topic['topic_id'])));
			
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
	 * Controller for route /tutorials/tricks/familien
	 *
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 */
	public function tutorials_tricks_families()
	{
		$this->template->assign_vars(array(
			'S_GPC_TUTORIALS_ACTIVE' => true,
		));
		return $this->helper->render('tricks_families.html');
	}

	/**
	 * Controller for route /tutorials/search
	 *
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 */
	public function tutorials_search()
	{
		$this->template->assign_vars(array(
			'S_GPC_TUTORIALS_ACTIVE' => true,
			'S_RH_TOPICTAGS_INCLUDE_NG_TAGS_INPUT'		=> true,
			'S_RH_TOPICTAGS_INCLUDE_CSS'				=> true,				
		));

		// TODO
		$tags = array('beginner', 'trick', 'pen');
		
	for ($i = 0, $size = sizeof($tags); $i < $size; $i++)
		{
			$this->template->assign_block_vars('rh_topictags_suggestions', array(
				'LINK' => '#',
				'NAME' => $tags[$i],
			));
		}
		
		$tutotials = array(
			array(
				'link' => '#',
				'tags' => 'asdf, asdfe',
			),
			array(
				'link' => '#',
				'tags' => 'asdf',
			),
		);
		for ($i = 0, $size = sizeof($tutotials); $i < $size; $i++)
		{
			$this->template->assign_block_vars('tutorial', array(
				'TITLE' => 'A title ' . $i,
				'LINK' => $tutotials[$i]['link'],
				'TAGS' => $tutotials[$i]['tags'],
			));
		}
		return $this->helper->render('tag_search.html');
	}

}
