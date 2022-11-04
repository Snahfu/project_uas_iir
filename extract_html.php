<?php
function extract_html($url, $proxy)
{
	$response = array();
	$response['code'] = '';
	$response['message'] = '';
	$response['status'] = false;

	$agent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1';

	// Some websites require referrer
	$host = parse_url($url, PHP_URL_HOST);
	$scheme = parse_url($url, PHP_URL_SCHEME);
	$referrer = $scheme . '://' . $host;
	
	$curl = curl_init();

	curl_setopt($curl, CURLOPT_HEADER, false);

	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

	curl_setopt($curl, CURLOPT_URL, $url);

	curl_setopt($curl, CURLOPT_PROXY, $proxy);

	curl_setopt($curl, CURLOPT_USERAGENT, $agent);

	curl_setopt($curl, CURLOPT_REFERER, $referrer);

	curl_setopt($curl, CURLOPT_COOKIESESSION, 0);

	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);

	// allow to crawl https webpages

	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

	// the download speed must be at least 1 byte per second

	curl_setopt($curl, CURLOPT_LOW_SPEED_LIMIT, 1);

	// if the download speed is below 1 byte per second for more than 30 seconds curl will give up

	curl_setopt($curl, CURLOPT_LOW_SPEED_TIME, 30);

	$content = curl_exec($curl);

	$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	$response['code'] = $code;

	if ($content === false) {

		$response['status'] = false;

		$response['message'] = curl_error($curl);
	} else {

		$response['status'] = true;

		$response['message'] = $content;
	}

	curl_close($curl);

	return $response;
}
