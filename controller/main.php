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
	 * Generates a link to the search for the given tags.
	 *  
	 * @param array $tags
	 */
	private function get_search_link(array $tags)
	{
		$size = sizeof($tags);
		if (!($size > 0))
		{
			return $this->helper->route('gpc_main_controller_tutorials_search');
		}

		for ($i = 0; $i < $size; $i++)
		{
			$tags[$i] = urlencode($tags[$i]);
		}
		return $this->remove_community(
			$this->helper->route('gpc_main_controller_tutorials_search', 
				array(
					'tags' => join(',', $tags)
				)));
	}
	
	/**
	 * Controller for route /tutorials/tricks/familien
	 *
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 */
	public function tutorials_tricks_families()
	{
		$this->template->assign_vars(array(
			'S_GPC_TUTORIALS_ACTIVE'	=> true,
			'U_GPC_SEARCH_AROUND'		=> $this->get_search_link(array('around')),
			'U_GPC_SEARCH_PASS'			=> $this->get_search_link(array('pass')),
			'U_GPC_SEARCH_SONIC'		=> $this->get_search_link(array('sonic')),
			'U_GPC_SEARCH_CHARGE'		=> $this->get_search_link(array('charge')),
			'U_GPC_SEARCH_INFINITY'		=> $this->get_search_link(array('infinity')),
			'U_GPC_SEARCH_SPINS'		=> $this->get_search_link(array('spins')),
			'U_GPC_SEARCH_SHADOW'		=> $this->get_search_link(array('shadow')),
			'U_GPC_SEARCH_ARTISTIC'		=> $this->get_search_link(array('artistic')),
			'U_GPC_SEARCH_OTHER'		=> $this->get_search_link(array('sonstige')),
		));
		return $this->helper->render('tricks_families.html');
	}
	
	/**
	 * Controller for route /tutorials/pens/familien
	 *
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 */
	public function tutorials_pens_families()
	{	
		$this->template->assign_vars(array(
			'S_GPC_TUTORIALS_ACTIVE'	=> true,
		));
		return $this->helper->render('pens_families.html');
	}

	/**
	 * Controller for route /tutorials/search
	 * shows a list of topics that have the given $tags assigned
	 *
	 * @param $tags tags seperated by comma (",")
	 * @param $mode the mode indicates whether all tags (AND, default) or any tag (OR) should be assigned to the resulting topics
	 * @param casesensitive wether to search case-sensitive (true) or -insensitive (false, default)
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 */
	public function tutorials_search($tags, $mode, $casesensitive)
	{
		global $user, $phpbb_container, $config, $phpbb_root_path, $request;
		$form_action_route = $this->get_search_link(array());
		$form_action_route = $this->remove_community($form_action_route);
		$this->template->assign_vars(array(
			'S_GPC_TUTORIALS_ACTIVE'				=> true,
			'S_RH_TOPICTAGS_INCLUDE_NG_TAGS_INPUT'	=> true,
			'S_RH_TOPICTAGS_INCLUDE_CSS'			=> true,
			'U_SEARCH_ROUTE'						=> $form_action_route,
		));

		// TODO
		$tag_suggestions = array('beginner', 'trick', 'pen', 'around', 'pass', 'sonic', 'charge', 'infinity', 'spins', 'shadow', 'artistic', 'sonstige');
		
		for ($i = 0, $size = sizeof($tag_suggestions); $i < $size; $i++)
		{
			$this->template->assign_block_vars('rh_topictags_suggestions', array(
				'LINK' => '#',
				'NAME' => $tag_suggestions[$i],
			));
		}
		
		// validate mode
		// default == AND
		$mode = ($mode == 'OR' ? 'OR' : 'AND');
	
		$tags = explode(',', urldecode($tags));
		// remove possible duplicates
		$tags = array_unique($tags);
		$all_tags = $this->tags_manager->split_valid_tags($tags);
			
		$tutorials = array();
		$tags = $all_tags['valid'];
		if (sizeof($tags) > 0)
		{
			$this->template->assign_var('RH_TOPICTAGS', 
				base64_encode(json_encode($tags)));
			
			$pagination = $phpbb_container->get('pagination');
			
			$start = $request->variable('start', 0);
			$limit = 100; // $config['topics_per_page'];
			
			$topics_count = $this->tags_manager->count_topics_by_tags($tags, 
				$start, $limit, $mode, $casesensitive);
			$start = $pagination->validate_start($start, 
				$config['topics_per_page'], $topics_count);
			
			$topics = $this->tags_manager->get_topics_by_tags($tags, $start, 
				$limit, $mode, $casesensitive);
			$base_url = $this->get_search_link($tags);
			
			// $pagination->generate_template_pagination($base_url, 'pagination', 'start',
			// $topics_count, $config['topics_per_page'], $start);
			
			$user->add_lang('viewforum');
			// $this->template->assign_var('TOTAL_TOPICS', $user->lang('VIEW_FORUM_TOPICS',
			// $topics_count));
			
			global $phpEx, $auth, $phpbb_dispatcher, $template;
			
			$phpbb_content_visibility = $phpbb_container->get(
				'content.visibility');
			include_once ($phpbb_root_path . 'includes/functions_display.' .
				 $phpEx);
			
			foreach ($topics as $topic)
			{
				$topic_id = $topic['topic_id'];
				$row = $topic;
				$s_type_switch = 0;
				
				$topic_forum_id = ($row['forum_id']) ? (int) $row['forum_id'] : $forum_id;
				
				// This will allow the style designer to output a different header
				// or even separate the list of announcements from sticky and normal topics
				$s_type_switch_test = ($row['topic_type'] == POST_ANNOUNCE ||
					 $row['topic_type'] == POST_GLOBAL) ? 1 : 0;
				
				// Replies
				$replies = $phpbb_content_visibility->get_count('topic_posts', 
					$row, $topic_forum_id) - 1;
				
				if ($row['topic_status'] == ITEM_MOVED)
				{
					$topic_id = $row['topic_moved_id'];
					$unread_topic = false;
				}
				else
				{
					$unread_topic = (isset($topic_tracking_info[$topic_id]) &&
						 $row['topic_last_post_time'] >
						 $topic_tracking_info[$topic_id]) ? true : false;
				}
				// Generate all the URIs ...
				$view_topic_url_params = 'f=' . $row['forum_id'] . '&t=' . $topic_id;
				$view_topic_url = append_sid(
					"{$phpbb_root_path}viewtopic.$phpEx", $view_topic_url_params);
				
				$tutorials[] = array(
					'title' => censor_text($row['topic_title']),
					'link' => $view_topic_url,
					'topic_id' => $topic_id,
					'tags' => join(', ', 
						$this->tags_manager->get_assigned_tags($topic_id)),
					'replies' => $replies,
					'views' => $row['topic_views'],
					'author' => get_username_string('full', 
						$row['topic_poster'], $row['topic_first_poster_name'], 
						$row['topic_first_poster_colour'])
				);
			}
		}
		$this->template->assign_var('TUTORIALS', base64_encode(json_encode($tutorials)));

		return $this->helper->render('tag_search.html');
	}

}
