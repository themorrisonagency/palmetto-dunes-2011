<?php
	// Supply a user id and an access token
	$userid = "1397673599";
  $accessToken = "1397673599.41c4255.f78a96711ad74bd9ae5c52a06a5747e2";

	// Gets our data
	function fetchData($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		$result = curl_exec($ch);
		curl_close($ch); 
		return $result;
	}

	// Pulls and parses data.
	$result = fetchData("https://api.instagram.com/v1/users/{$userid}/media/recent/?access_token={$accessToken}");
	$result = json_decode($result);
?>

<!-- Renders images. @Options (thumbnail,low_resoulution, high_resolution) -->
<?php foreach ($result->data as $post): ?>
	<a class="group" rel="group1" href="<?= $post->images->standard_resolution->url ?>"><img src="<?= $post->images->standard_resolution->url ?>"></a>
<?php endforeach ?>