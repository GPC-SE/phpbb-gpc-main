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
	 * @param String $bbcode_bitfield the bbcode_bitfield of the post row
	 * @param int $limit the maximum length of the preview text
	 * @return the trimmed preview text.
	 *
	 * @static
	 * @author MichaelC, Robert Heim
	 */
	public static function make_preview_text_from_post($post_text, $bbcode_uid, $bbcode_bitfield, $limit)
	{
		$preview = $post_text;
		// remove youtube completely
		//$preview = trim(preg_replace('/(\[youtube\])(.*)(\[\/youtube\])/i', '', $preview));
		//$preview = self::bbcodeStripping($preview, $bbcode_uid);
		$bbcode_options = OPTION_FLAG_BBCODE + OPTION_FLAG_SMILIES + OPTION_FLAG_LINKS;
		$preview = generate_text_for_display($preview, $bbcode_uid, $bbcode_bitfield, $bbcode_options);
		$preview = self::cut_html($preview, $limit);
		/*$preview = trim(preg_replace('#http(?:\:|&\#58;)//\S+#', '', $preview));
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
		}*/
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
	
	private static function cut_html ($html, $limit) {
	    $dom = new DOMDocument();
	    $dom->loadHTML(mb_convert_encoding("<div>{$html}</div>", "HTML-ENTITIES", "UTF-8"), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
	    self::cut_html_recursive($dom->documentElement, $limit);
	    return substr($dom->saveHTML($dom->documentElement), 5, -6);
	}

	private static function cut_html_recursive ($element, $limit) {
	    if($limit > 0) {
		if($element->nodeType == 3) {
		    $limit -= strlen($element->nodeValue);
		    if($limit < 0) {
			$element->nodeValue = substr($element->nodeValue, 0, strlen($element->nodeValue) + $limit);
		    }
		}
		else {
		    for($i = 0; $i < $element->childNodes->length; $i++) {
			if($limit > 0) {
			    $limit = self::cut_html_recursive($element->childNodes->item($i), $limit);
			}
			else {
			    $element->removeChild($element->childNodes->item($i));
			    $i--;
			}
		    }
		}
	    }
	    return $limit;
	}
}
