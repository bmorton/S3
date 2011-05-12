<?php
/**
 * Wrapper Component for Amazon's Simple Storage Service
 *
 * Uses the official Amazon Web Services SDK.
 *
 * @package default
 * @author Brian Morton
 */
class S3AssetComponent extends Object {

/**
 * AWS S3 connection object
 *
 * @var object
 */
	var $s3 = null;

/**
 * AWS Account Key
 *
 * @var string
 */
	var $key = null;

/**
 * AWS Account Secret
 *
 * @var string
 */
	var $secretKey = null;

/**
 * Default bucket to use if one isn't specified.
 *
 * @var string
 */
	var $defaultBucket = null;

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
 * Handle creating an S3 object if it hasn't been created yet.
 * Pass back a valid object to the requester.
 *
 * @author Brian Morton
 * @return object
 */
	function &connection() {
		// If key & secretKey weren't defined, we can't do anything.
		if (is_null($this->key) || is_null($this->secretKey)) {
			throw new Exception("Can't establish a connection to S3 because the key and secretKey are null.");
		}
		
		// If the S3 object hasn't been created yet, load it here (lazy loading ftw)
		if (!$this->s3) {
			App::import('Vendor', 'AWS', array('file' => 'aws' . DS . 'sdk.class.php', 'plugin' => 'S3'));
			$this->s3 = new AmazonS3($this->key, $this->secretKey);
		}
		
		return $this->s3;
	}
	
/**
 * Wrapper for listing buckets on S3
 *
 * @param string $pcre An optional regular expression for matching bucket names.
 * @return array An array of the buckets that exist on S3
 * @author Brian Morton
 */
	function getBucketList($pcre = null) {
		return $this->connection()->get_bucket_list($pcre);
	}

/**
 * Wrapper for creating objects in an S3 bucket
 *
 * @param array $arguments	An array of arguments that must include a filename and a bucket if the
 *							default is not set or shouldn't be used.  Either body or fileUpload 
 *							must also be set so we know what to send to S3.  Optionally, an options
 *							array argument can be set that conforms to $opt on:
 *							http://docs.amazonwebservices.com/AWSSDKforPHP/latest/#m=AmazonS3/create_object
 * @return object CFResponse
 * @author Brian Morton
 */
	function createObject($arguments = array()) {
		$defaults = array(
			'bucket' => $this->defaultBucket
		);
		
		extract(array_merge($defaults, $arguments));
		
		if (isset($body)) {
			$options['body'] = $body;
		}
		
		if (isset($fileUpload)) {
			$options['fileUpload'] = $fileUpload;
		}
		
		return $this->connection()->create_object($bucket, $filename, $options);
	}

/**
 * Gets the web-accessible URL for the Amazon S3 object or generates a time-limited signed request for a private file.
 *
 * @param array $arguments	An array of arguments that must contain:
 *								* filename
 *								* bucket (optional if defaultBucket specified)
 *
 *							And can optionally contain:
 *								* preauth - Specifies that a presigned URL for this request should be returned. May be
 *									passed as a number of seconds since UNIX Epoch, or any string compatible with
 *									strtotime().
 *								* options (array) - must conform to $opt on:
 *									http://docs.amazonwebservices.com/AWSSDKforPHP/latest/#m=AmazonS3/get_object_url
 * @return void
 * @author Brian Morton
 */
	function getObjectUrl($arguments = array()) {
		$defaults = array(
			'bucket' => $this->defaultBucket,
			'preauth' => 0,
			'options' => null,
			'filename' => null
		);
		
		extract(array_merge($defaults, $arguments));
		
		return $this->connection()->get_object_url($bucket, $filename, $preauth, $options);
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