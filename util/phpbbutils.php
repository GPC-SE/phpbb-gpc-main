<?php
/**
 *
 * @package phpBB Extension - GPC Main
 * @copyright (c) 2014 Robet Heim
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace gpc\main\util;

class phpbbutils {

	/**
	 * Trims bbcode containing text to $limit characters by removing all bbcodes.
	 *
	 * @param String $post_text the text of the post to trim
	 * @param String $bbcode_uid the bbcode_uid of the post row
	 * @param int $limit the maximum length of the preview text
	 * @return the trimmed preview text.
	 *
	 * @static
	 * @author MichaelC, Robert Heim
	 */
	public static function make_preview_text_from_post($post_text, $bbcode_uid, $limit)
	{
		$preview = $post_text;
		// remove youtube completely
		//$preview = trim(preg_replace('/(\[youtube\])(.*)(\[\/youtube\])/i', '', $preview));
		$preview = self::bbcodeStripping($preview, $bbcode_uid);
		$preview = trim(preg_replace('#http(?:\:|&\#58;)//\S+#', '', $preview));
		// Decide how large the preview text should be
		$preview_max_len = $limit;
		$preview_len = strlen($preview);
		if ($preview_len > $preview_max_len) {
			// we will add " ..." in the end -> 4 characters
			$preview_max_len -= 4;
			// If the first thing is a link, nuke it
			$preview_clean = str_replace('&#58;', ':', $preview);
			if (substr($preview_clean, 0, 8) == 'https://' || substr($preview_clean, 0, 7) == 'http://') {
				$preview = preg_replace('/^(\S*)\s/', '', $preview);
			}
			// Truncate to the maximum length
			$preview = substr($preview, 0, $preview_max_len);

			// See if there is a nice place to break close to the max length
			$breakchars = array(' ', ',', '.');
			$clean_break_pos = 0;
			foreach ($breakchars as $char) {
				$clean_break_pos_new = strrpos($preview, $char);
				$clean_break_pos = $clean_break_pos_new > $clean_break_pos ? $clean_break_pos_new : $clean_break_pos;
			}
			if ($clean_break_pos) {
				$preview = substr($preview, 0, $clean_break_pos);
			}
			$preview .= ' ...';
		}
		return $preview;
	}

	/**
	 * Strip bbcodes from a post content
	 *
	 * @param  string $text The raw content from the database to strip bbcodes from
	 * @param  string $uid  The $uid used in encoding/decoding the bbcode
	 * @return string $text The post with bbcodes stripped
	 * @static
	 */
	private static function bbcodeStripping($text, $uid)
	{

		return strip_bbcode($text, $uid);
	}
}
