<?php
/**
 * set document type
 * @param string $type type of document
 */

set_time_limit(0);
//echo getcwd();




$sroot=$_SERVER["DOCUMENT_ROOT"];
require_once $sroot.'/lib/s3/aws-autoloader.php';

if(!isset($dotenv)){
	require_once realpath($sroot . '/assets/plugins/env/autoload.php');
	$dotenv = Dotenv\Dotenv::createImmutable($sroot.'/');
	$dotenv->load();
}

use Aws\S3\S3Client;
//use Aws\Credentials\CredentialProvider;
use Aws\S3\Exception\S3Exception;

//error_reporting(0);
ini_set('max_execution_time', 0);

$profile = 'default';
//$path = $sroot.'/lib/s3/credentials.ini';

//$provider = CredentialProvider::ini($profile, $path);
//$provider = CredentialProvider::memoize($provider);

$s3Client = new S3Client([
	'region'      => 'us-west-2',
	'version'     => 'latest',
	'credentials' => [
			 'key' => $_ENV['aws_access_key_id'],
			 'secret' => $_ENV['aws_secret_access_key']
	 ]
]);

function set_content_type($type = 'application/json') {
    header('Content-Type: '.$type);
}

/**
 * Read CSV from URL or File
 * @param  string $filename  Filename
 * @param  string $delimiter Delimiter
 * @return array            [description]
 */
function read_csv($filename, $delimiter = ",") {
    $file_data = array();
    $handle = @fopen($filename, "r") or false;
    if ($handle !== FALSE) {
        while (($data = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
            $file_data[] = $data;
        }
        fclose($handle);
    }
    return $file_data;
}

/**
 * Print Log to the page
 * @param  mixed  $var    Mixed Input
 * @param  boolean $pre    Append <pre> tag
 * @param  boolean $return Return Output
 * @return string/void     Dependent on the $return input
 */
function plog($var, $pre=true, $return=false) {
    $info = print_r($var, true);
    $result = $pre ? "<pre>$info</pre>" : $info;
    if ($return) return $result;
    else echo $result;
}

/**
 * Log to file
 * @param  string $log Log
 * @return void
 */
function elog($log, $fn = "debug.log") {
    $fp = fopen($fn, "a");
    fputs($fp, "[".date("d-m-Y h:i:s")."][Log] $log\r\n");
    fclose($fp);
}

function getpresignedurl($object, $bucket = '', $expiration = '')
{
	$bucket = trim($bucket ?: $this->getDefaultBucket(), '/');
	if (empty($bucket)) {
		throw new InvalidDomainNameException('An empty bucket name was given');
	}
	if ($expiration) {
		$command = $this->client->getCommand('GetObject', ['Bucket' => $bucket, 'Key' => $object]);
		return $this->client->createPresignedRequest($command, $expiration)->getUri()->__toString();
	} else {
		return $this->client->getObjectUrl($bucket, $object);
	}
}

function checks3img($keyname,$foldername="",$noimage=""){
	global $s3Client;
	if($foldername=="") return false;
	$keyname=@trim($keyname);
	$infotarget = $s3Client->doesObjectExist('datahub360', $foldername.$keyname);
	if($keyname != "" and $infotarget)
	{
		$cmd = $s3Client->getCommand('GetObject', [
			'Bucket' => 'datahub360',
			'Key'    => $foldername.$keyname
		]);

		$request = $s3Client->createPresignedRequest($cmd, '+3 minutes');
		return (string) $request->getUri();
	}elseif($noimage !=""){
		$infotarget = $s3Client->doesObjectExist('datahub360',$foldername.$noimage);
		if($infotarget)
		{
			$cmd = $s3Client->getCommand('GetObject', [
				'Bucket' => 'datahub360',
				'Key'    => $foldername.$noimage
			]);

			$request = $s3Client->createPresignedRequest($cmd, '+2 minutes');
			return (string) $request->getUri();
		}else{
			return false;
			//logoff
		}
	}else{
		return false;
		//logoff
	}
}

function getpresignedfile($keyname,$foldername,$forcedownload=""){
	global $s3Client;
	if(empty($foldername)) return false;
	$keyname=@trim($keyname);
	$infotarget = $s3Client->doesObjectExist('datahub360', $foldername.$keyname);
	if($keyname != "" and $infotarget)
	{
		if(empty($forcedownload)){
			$cmd = $s3Client->getCommand('GetObject', [
				'Bucket' => 'datahub360',
				'Key'    => $foldername.$keyname
			]);
		}else{
			$cmd = $s3Client->getCommand('GetObject', [
				'Bucket' => 'datahub360',
				'Key'    => $foldername.$keyname,
				'ResponseContentDisposition' => 'attachment; filename="' . $keyname . '"',
			]);		
		}

		$request = $s3Client->createPresignedRequest($cmd, '+3 minutes');
		return (string) $request->getUri();
	}else return "";
}

if(!function_exists('money_format')){
  function money_format($novalue,$value=0) {
    if ($value<0) return "-".money_format(-$value);
    return  number_format($value, 2);
  }

  function money_format1($format, $number) {
          $regex = '/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?' .
                  '(?:#([0-9]+))?(?:\.([0-9]+))?([in%])/';
          if (setlocale(LC_MONETARY, 0) == 'C') {
              setlocale(LC_MONETARY, '');
          }
          $locale = localeconv();
          preg_match_all($regex, $format, $matches, PREG_SET_ORDER);
          foreach ($matches as $fmatch) {
              $value = floatval($number);
              $flags = array(
                  'fillchar' => preg_match('/\=(.)/', $fmatch[1], $match) ?
                          $match[1] : ' ',
                  'nogroup' => preg_match('/\^/', $fmatch[1]) > 0,
                  'usesignal' => preg_match('/\+|\(/', $fmatch[1], $match) ?
                          $match[0] : '+',
                  'nosimbol' => preg_match('/\!/', $fmatch[1]) > 0,
                  'isleft' => preg_match('/\-/', $fmatch[1]) > 0
              );
              $width = trim($fmatch[2]) ? (int) $fmatch[2] : 0;
              $left = trim($fmatch[3]) ? (int) $fmatch[3] : 0;
              $right = trim($fmatch[4]) ? (int) $fmatch[4] : $locale['int_frac_digits'];
              $conversion = $fmatch[5];

              $positive = true;
              if ($value < 0) {
                  $positive = false;
                  $value *= -1;
              }
              $letter = $positive ? 'p' : 'n';

              $prefix = $suffix = $cprefix = $csuffix = $signal = '';

              $signal = $positive ? $locale['positive_sign'] : $locale['negative_sign'];
              switch (true) {
                  case $locale["{$letter}_sign_posn"] == 1 && $flags['usesignal'] == '+':
                      $prefix = $signal;
                      break;
                  case $locale["{$letter}_sign_posn"] == 2 && $flags['usesignal'] == '+':
                      $suffix = $signal;
                      break;
                  case $locale["{$letter}_sign_posn"] == 3 && $flags['usesignal'] == '+':
                      $cprefix = $signal;
                      break;
                  case $locale["{$letter}_sign_posn"] == 4 && $flags['usesignal'] == '+':
                      $csuffix = $signal;
                      break;
                  case $flags['usesignal'] == '(':
                  case $locale["{$letter}_sign_posn"] == 0:
                      $prefix = '(';
                      $suffix = ')';
                      break;
              }
              if (!$flags['nosimbol']) {
                  $currency = $cprefix .
                          ($conversion == 'i' ? $locale['int_curr_symbol'] : $locale['currency_symbol']) .
                          $csuffix;
              } else {
                  $currency = $cprefix .$csuffix;
              }
              $space = $locale["{$letter}_sep_by_space"] ? ' ' : '';

              $value = number_format($value, $right, $locale['mon_decimal_point'], $flags['nogroup'] ? '' : $locale['mon_thousands_sep']);
              $value = @explode($locale['mon_decimal_point'], $value);

              $n = strlen($prefix) + strlen($currency) + strlen($value[0]);
              if ($left > 0 && $left > $n) {
                  $value[0] = str_repeat($flags['fillchar'], $left - $n) . $value[0];
              }
              $value = implode($locale['mon_decimal_point'], $value);
              if ($locale["{$letter}_cs_precedes"]) {
                  $value = $prefix . $currency . $space . $value . $suffix;
              } else {
                  $value = $prefix . $value . $space . $currency . $suffix;
              }
              if ($width > 0) {
                  $value = str_pad($value, $width, $flags['fillchar'], $flags['isleft'] ?
                                  STR_PAD_RIGHT : STR_PAD_LEFT);
              }

              $format = str_replace($fmatch[0], $value, $format);
          }
          return $format;
      }


}
?>
