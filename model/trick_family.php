<?php
/**
 *
 * @package phpBB Extension - GPC Main
 * @copyright (c) 2016 Robet Heim
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */
namespace gpc\main\model;

class trick_family
{

	protected $name;

	protected $description;

	protected $tagname;

	protected $relative_img_url;

	public function __construct(
		$name,
		$description,
		$tagname,
		$relative_img_url)
	{
		$this->set_name($name);
		$this->set_description($description);
		$this->set_tagname($tagname);
		$this->set_relative_img_url($relative_img_url);
	}

	public function set_name($name)
	{
		$this->name = $name;
	}

	public function get_name()
	{
		return $this->name;
	}

	public function get_description()
	{
		return $this->description;
	}

	public function set_description($description)
	{
		$this->description = $description;
	}

	public function set_tagname($tagname)
	{
		$this->tagname = $tagname;
	}

	public function get_tagname()
	{
		return $this->tagname;
	}

	public function set_relative_img_url($relative_img_url)
	{
		return $this->relative_img_url = $relative_img_url;
	}

	public function get_relative_img_url()
	{
		return $this->relative_img_url;
	}

}