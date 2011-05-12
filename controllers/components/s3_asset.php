<?php
/**
 * Wrapper Component for Amazon's Simple Storage Service
 *
 * Uses the official Amazon Web Services SDK.
 *
 * @package default
 * @author Brian Morton
 */
App::import('Lib', 'S3.CakeS3');
class S3AssetComponent extends CakeS3 {
/**
 * Component initializer that gets called before Controller::beforeFilter()
 *
 * @param string $controller 
 * @param string $settings 
 * @return void
 * @author Brian Morton
 */
	function initialize(&$controller, $settings = array()) {
		// Save the controller reference for later use
		$this->controller =& $controller;
		
		// Set our settings if they are passed in
		if (isset($settings['key'])) {
			$this->key = $settings['key'];
		}
		
		if (isset($settings['secretKey'])) {
			$this->secretKey = $settings['secretKey'];
		}
		
		if (isset($settings['defaultBucket'])) {
			$this->defaultBucket = $settings['defaultBucket'];
		}
	}
	
/**
 * Empty placeholder for functionality called after Controller::beforeFilter()
 *
 * @param string $controller 
 * @return void
 * @author Brian Morton
 */
	function startup(&$controller) {
		
	}

/**
 * Empty placeholder for code called after Controller::beforeRender()
 *
 * @param string $controller 
 * @return void
 * @author Brian Morton
 */
	function beforeRender(&$controller) {
		
	}

/**
 * Empty placeholder for code called after Controller::render()
 *
 * @param string $controller 
 * @return void
 * @author Brian Morton
 */
	function shutdown(&$controller) {
		
	}

/**
 * Empty placeholder for code called before Controller::redirect()
 *
 * @param string $controller 
 * @param string $url 
 * @param string $status 
 * @param string $exit 
 * @return void
 * @author Brian Morton
 */
	function beforeRedirect(&$controller, $url, $status = null, $exit = true) {
		
	}
}
?>