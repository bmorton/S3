<?php
/**
 * A basic CakePHP helper for generating S3 links and images.
 *
 * @package default
 * @author Brian Morton
 */
class S3AssetHelper extends AppHelper {
/**
 * CakePHP helpers to include in this helper
 *
 * @var string
 */
	var $helpers = array('Html');
	
/**
 * Hold the library the helps us talk to S3
 *
 * @var string
 */
	var $s3 = null;
	
	function __construct() {
		$return = App::import('Lib', 'S3.CakeS3');
		$this->s3 = new CakeS3();
		$this->s3->key = Configure::read('AWS.S3.key');
		$this->s3->secretKey = Configure::read('AWS.S3.secretKey');
	}
	
	function image($bucket, $filename, $optionsS3 = array(), $htmlAttributes = null) {
		if (isset($optionsS3['preauth'])) {
			$preauth = $optionsS3['preauth'];
		} else {
			$preauth = 0;
		}
		
		$image = $this->s3->getObjectUrl(array(
			'bucket' => $bucket,
			'filename' => $filename,
			'preauth' => $preauth,
			'options' => $optionsS3
		));
		
		return $this->Html->image($image, $htmlAttributes);
	}
	
	function link($title, $objectOptions, $linkOptions = array(), $confirmMessage = false) {
		$defaults = array(
			'bucket' => '',
			'filename' => '',
			'preauth' => 0,
			'options' => null
		);
		
		extract(array_merge($defaults, $objectOptions));
		
		$url = $this->s3->getObjectUrl(array(
			'bucket' => $bucket,
			'filename' => $filename,
			'preauth' => $preauth,
			'options' => $options
		));
		
		return $this->Html->link($title, $url, $linkOptions, $confirmMessage);
	}
}
?>