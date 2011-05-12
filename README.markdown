# Amazon S3 Asset Wrapper for CakePHP

by Brian Morton (bmorton@sdreader.com)

Copyright (c) 2011 Brian Morton.  All rights reserved.

## License
Permission is hereby granted, free of charge, to any person obtaining a
copy of this software and associated documentation files (the "Software"),
to deal in the Software without restriction, including without limitation
the rights to use, copy, modify, merge, publish, distribute, sublicense,
and/or sell copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
DEALINGS IN THE SOFTWARE.



## What's included

This plugin has 2 parts: a component and a helper.  Both currently only support very basic S3 functionality, but the hope is that you will fork this, add more S3 calls, and send back a pull request :)



## The component

There are currently only 3 defined methods for the component. To use the component, you must first load the component in your controller:

```
var $components = array('S3.S3Asset' => array('key' => '01234567890', 'secretKey' => 'yourAmazonSecretKey'));
```



### Get bucket list

This method will retrieve an array of buckets that you have access to on S3.  You can optionally pass it a regular expression as its one and only argument to filter the array returned.

```
$buckets = $this->S3Asset->getBucketList();
```



### Create object

This method will create an object in the S3 bucket.  It takes a single array as its argument that must include a filename and a bucket.  Either body or fileUpload must also be set so we know what to send to S3.  Optionally, an options array argument can be set that conforms to $opt on: http://docs.amazonwebservices.com/AWSSDKforPHP/latest/#m=AmazonS3/create_object

```
$file = $this->S3Asset->createObject(array(
	'bucket' => 'bmorton',
	'filename' => 'zelda.png',
	'fileUpload' => '/home/bmorton/zelda.png'
));
```

The object returned is a CFResponse object: http://docs.amazonwebservices.com/AWSSDKforPHP/latest/index.html#i=CFResponse



### Get a (signed) URL

This method gets the web-accessible URL for the Amazon S3 object or generates a time-limited signed request for a private file.

It also takes a single array as its argument that must contain filename and bucket keys.  It can optionally contain keys for preauth and options (again, an array that conforms to $opt on: http://docs.amazonwebservices.com/AWSSDKforPHP/latest/#m=AmazonS3/get_object_url).  The preauth key can be passed as a number of seconds since UNIX Epoch or any string compatible with strtotime().

```
$image = $this->S3Asset->getObjectUrl(array(
	'bucket' => 'bmorton',
	'filename' => 'zelda.png',
	'preauth' => '1 hour'
));
```

This method will return a string such as: `http://bmorton.s3.amazonaws.com/zelda.png?AWSAccessKeyId=0123456789&Expires=0123456789&Signature=%2FJKasjkasASKJsakjASJas%3D`



## Helper

The helper wraps the above method calls with the core HTML helper.



### Image helper

Generate a well-formed HTML image tag using S3 as the image source.  Accepts an optional 4th argument for an array of HTML attributes to be passed through to $html->link().

```
// should be: image(bucketName, fileName, additionalAmazonOptionsArray, htmlAttributesArray)
echo $this->S3Asset->image('bmorton', 'zelda.png', array('preauth' => '1 hour'));
```



### Link helper

Generate a well-formed HTML link using S3 as the destination.  Follows a similar convention as $html->link() and should be passed: `title`, `array of S3 options with bucket, filename, preauth`, `array of CakePHP link options (optional)`, `string of confirmation message (optional)`

```
echo $this->S3Asset->link('Link to an image!', array(
	'bucket' => 'bmorton',
	'filename' => 'zelda.png',
	'preauth' => '1 hour'
));
```
