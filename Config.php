<?php namespace Model\ReCaptcha;

use Model\Core\Module_Config;

class Config extends Module_Config
{
	/**
	 */
	protected function assetsList()
	{
		$this->addAsset('config', 'config.php', function () {
			return '<?php
$config = ' . var_export([
					'public' => null,
					'private' => null,
				], true) . ";\n";
		});
	}
}
