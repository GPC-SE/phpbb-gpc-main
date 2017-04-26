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
* Handles all functionallity regarding the gpc-header.
*/
class gpc_header_manager
{
	protected $template;
	protected $helper;

	public function __construct(
				\phpbb\template\template $template,
				\phpbb\controller\helper $helper
	)
	{
		$this->template	= $template;
		$this->helper	= $helper;
	}

	public function assign_header_vars()
	{
		global $phpbb_root_path, $phpEx;
		$home_link = '/';

		// TODO pen_trading_link
		$forum_id = 27;
		$topic_id = 26217;
		$pen_trading_tutorial_url_params = 'f=' . $forum_id . '&amp;t=' . $topic_id;
		$pen_trading_tutorial_url = append_sid("{$phpbb_root_path}viewtopic.$phpEx", $pen_trading_tutorial_url_params);
		// TODO current thread (the next line needs to be removed, once the thread exists in the new board)
		$pen_trading_tutorial_url  = "http://forum.penspinning.de/viewtopic.php?f=27&t=26217";

		// on page server_costs we won't show the hint to disable adblocker (but we show ads if not blocked)
		$show_ads_alternative_if_blocked = !(!strpos($this->helper->get_current_url(), 'server_costs') === false);

		$this->template->assign_vars(array(
			'GPC_STYLE_PATH'	=> $home_link . 'community/ext/gpc/main/styles/all/',
			'U_GPC_HOME'		=> $home_link,
			'U_GPC_IMPRESSUM'	=> $this->remove_community($this->helper->route('gpc_main_controller_impressum')),
			'U_GPC_PRIVACY'		=> $this->remove_community($this->helper->route('gpc_main_controller_privacy')),
			'U_GPC_VIDEOS'		=> $this->remove_community($this->helper->route('gpc_main_controller_videos')),
			'U_GPC_TUTORIALS_TRICKS_ALL'		=> $this->remove_community($this->helper->route('gpc_main_controller_tutorials_search', array('tags' => 'trick'))),
			'U_GPC_TUTORIALS_TRICKS_BEGINNER'	=> $this->remove_community($this->helper->route('gpc_main_controller_tutorials_search', array('tags' => 'trick,beginner'))),
			'U_GPC_TUTORIALS_TRICKS_FAMILIES'	=> $this->remove_community($this->helper->route('gpc_main_controller_tutorials_tricks_families')),
			'U_GPC_TUTORIALS_PENS_ALL'			=> $this->remove_community($this->helper->route('gpc_main_controller_tutorials_search', array('tags' => 'pen'))),
			'U_GPC_TUTORIALS_PENS_BEGINNER'		=> $this->remove_community($this->helper->route('gpc_main_controller_tutorials_search', array('tags' => 'pen,beginner'))),
			'U_GPC_TUTORIALS_PENS_FAMILIES'		=> $this->remove_community($this->helper->route('gpc_main_controller_tutorials_pens_families')),
			'U_GPC_TUTORIALS_PENS_TRADING'		=> $pen_trading_tutorial_url,
			'U_GPC_TUTORIALS_BEGINNER_GUIDES'	=> $this->remove_community($this->helper->route('gpc_main_controller_tutorials_search', array('tags' => 'beginner'))),
			'U_GPC_TUTORIALS_OTHER'				=> $this->remove_community($this->helper->route('gpc_main_controller_tutorials_search', array('tags' => 'sonstige'))),
			'U_GPC_FAQ'						=> $this->remove_community($this->helper->route('gpc_main_controller_faq')),
			'U_GPC_SHOP'						=> $this->remove_community($this->helper->route('gpc_main_controller_shop')),
			'U_SERVER_COSTS'	=> $this->remove_community($this->helper->route('gpc_main_controller_server_costs')),
			'S_WERBUNG_ALTERNATIVE'				=> $show_ads_alternative_if_blocked,
		));

		/* determine the active menu item.
		* All main-sites nav-items are activated by their controller,
		* so we only need to check the community items here.
		*/
		// if the current url contains "/community/"
		if (!strpos($this->helper->get_current_url(), '/community/') === false)
		{
			$this->template->assign_vars(array(
				'S_GPC_COMMUNITY_ACTIVE' => true,
			));
		}
	}

	private function remove_community($str)
	{
		return str_replace('community/', '', $str);
	}
}
