<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

	/**
	 * Genereate GUID
	 * @param  boolean $prefix
	 * @param  boolean $braces
	 * @return GUID string
	 */
	public function generateGUID($prefix = false, $braces = false)
	{
		mt_srand((double) microtime() * 10000);
		$charid = strtoupper(md5(uniqid($prefix === false ? rand() : $prefix, true)));
        $hyphen = chr(45); // "-"
        $uuid = substr($charid, 0, 8) . $hyphen
        . substr($charid, 8, 4) . $hyphen
        . substr($charid, 12, 4) . $hyphen
        . substr($charid, 16, 4) . $hyphen
        . substr($charid, 20, 12);

        // Add brackets or not? "{" ... "}"
        return $braces ? chr(123) . $uuid . chr(125) : $uuid;
    }
}
