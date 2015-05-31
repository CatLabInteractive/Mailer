<?php
/**
 * Created by PhpStorm.
 * User: daedeloth
 * Date: 31/05/15
 * Time: 13:21
 */

namespace CatLab\Mailer\Models;


class Image {

	/**
	 * @var string
	 */
	private $path;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @param mixed $filename
	 * @return Image
	 */
	public static function fromMixed ($filename)
	{
		if ($filename instanceof Contact) {
			return $filename;
		}

		else {
			$value = new self ($filename);
			return $value;
		}
	}

	public function __construct ($path, $name = null)
	{
		$this->setPath ($path);
		if (!$name) {
			$name = basename ($path);
		}
		$this->setName ($name);
	}

	/**
	 * @return string
	 */
	public function getPath ()
	{
		return $this->path;
	}

	/**
	 * @param string $path
	 * @return self
	 */
	public function setPath ($path)
	{
		$this->path = $path;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName ()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return self
	 */
	public function setName ($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * Get mimetype
	 */
	public function getMimeType () {
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		return finfo_file ($finfo, $this->getPath ());
	}
}