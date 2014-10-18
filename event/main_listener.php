<?php
/**
*
* @package phpBB Extension - GPC main
* @copyright (c) 2014 Robet Heim
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace gpc\main\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class main_listener implements EventSubscriberInterface
{

	static public function getSubscribedEvents()
	{
		return array(
			'core.common'	=> 'common',
		);
	}

	protected $gpc_header_manager;

	public function __construct(
		\gpc\main\service\gpc_header_manager $gpc_header_manager
	)
	{
		$this->gpc_header_manager = $gpc_header_manager;
	}

	/**
	* PHPBBEvent core.common triggered on every page.
	*/
	public function common($event)
	{
		// always set the general template vars
		$this->gpc_header_manager->assign_header_vars();
	}
}
