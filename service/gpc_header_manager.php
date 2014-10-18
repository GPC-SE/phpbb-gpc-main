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
		global $phpbb_root_path;
		// helper->route might add ssid, so we cannot use it., remove gpc_main_controller2?

		$home_link = '/';

		$this->template->assign_vars(array(
			'GPC_STYLE_PATH'	=> $home_link . 'community/ext/gpc/main/styles/all/',
			'U_GPC_HOME'		=> $home_link,
			'U_GPC_IMPRESSUM'	=> $this->remove_community($this->helper->route('gpc_main_controller_impressum')),
			'U_GPC_VIDEOS'		=> $this->remove_community($this->helper->route('gpc_main_controller_videos')),
			'U_GPC_TUTORIALS'	=> $this->remove_community($this->helper->route('gpc_main_controller_tutorials')),
			'U_GPC_FAQS'		=> $this->remove_community($this->helper->route('gpc_main_controller_faqs')),
			'U_GPC_COMMUNITY'	=> $phpbb_root_path,
		));

		/* determine the active menu item.
		* All main-sites nav-items are activated by their controller,
		* so we only need to check the community items here.
		*/
		// if the current url contains "/community/"
		if (!strpos($this->helper->get_current_url(), '/community/') === false)
		{
			$active_str = 'S_GPC_COMMUNITY_ACTIVE';
			// contact page? if the current url contains "'community/memberlist.php?mode=contactadmin'"
			if (!strpos($this->helper->get_current_url(), 'community/memberlist.php?mode=contactadmin') === false)
			{
				$active_str = 'S_GPC_CONTACT_ACTIVE';
			}
			$this->template->assign_vars(array(
				$active_str => true,
			));
		}
	}

	private function remove_community($str)
	{
		return str_replace('community/', '', $str);
	}
}
