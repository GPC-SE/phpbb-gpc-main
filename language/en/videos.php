<?php
/**
*
* @package phpBB Extension - GPC Main
* @copyright (c) 2015 Robet Heim
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}
$lang = array_merge($lang, array(
	'VIDEOS_COUNT' => array(
		0 => 'No videos',
		1 => 'One video',
		2 => '%d videos'
	),
));
