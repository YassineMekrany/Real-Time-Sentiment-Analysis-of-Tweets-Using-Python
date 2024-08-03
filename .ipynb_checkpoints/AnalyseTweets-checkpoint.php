<?php
$token = '1774423146801598464-k4V8u1VP48gWJE2MlFS6ScsWUTqLB0';
$token_secret = 'W7J0ccsmZjcAslILqY6xsmcKsJfhdxXoYO1FqiphWtkF5';
$consumer_key = 'qAUN7zk2nw0bQ5ekfCISf9MAL';
$consumer_secret = 'q7l3R3tz8iZXg1ky7omohIbYDxqdJit4s94DHowgbw2g7Bc8WX';

$host = 'api.twitter.com';
$method = 'GET';
$path = '/1.1/statuses/user_timeline.json'; // API endpoint for user timeline

$query = array( // query parameters
    'screen_name' => 'twitterapi', // Twitter screen name for the user
    'count' => '5' // Number of tweets to retrieve
);

$oauth = array(
    'oauth_consumer_key' => $consumer_key,
    'oauth_token' => $token,
    'oauth_nonce' => md5(mt_rand()), // Generate a random nonce
    'oauth_timestamp' => time(),
    'oauth_signature_method' => 'HMAC-SHA1',
    'oauth_version' => '1.0'
);

// Encode all parameters before sorting
$encoded_oauth = array_map('rawurlencode', $oauth);
$encoded_query = array_map('rawurlencode', $query);

// Combine oauth and query parameters for signature base string
$params = array_merge($encoded_oauth, $encoded_query);
uksort($params, 'strcmp'); // Sort parameters by key

// Build the signature base string
$base_string = strtoupper($method) . '&' . rawurlencode("https://$host$path") . '&' . rawurlencode(http_build_query($params, '', '&'));

// Generate the signing key
$signing_key = rawurlencode($consumer_secret) . '&' . rawurlencode($token_secret);

// Generate the OAuth signature
$oauth_signature = base64_encode(hash_hmac('sha1', $base_string, $signing_key, true));

// Add the OAuth signature to the oauth array
$oauth['oauth_signature'] = $oauth_signature;

// Construct the Authorization header
$auth_header = 'OAuth ' . urldecode(http_build_query($oauth, '', ', '));

// Build the final URL with query parameters
$url = "https://$host$path?" . http_build_query($query);

// Initialize cURL session
$ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => $url,
    CURLOPT_HTTPHEADER => array("Authorization: $auth_header"),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false // Not recommended for production use
));

// Execute cURL request and retrieve response
$response = curl_exec($ch);
curl_close($ch);

// Decode JSON response into an associative array
$tweets = json_decode($response, true);

// Output tweets with links for URLs, mentions, and hashtags
foreach ($tweets as $tweet) {
    $text = $tweet['text'];
    $text = preg_replace('/(https?:\/\/\S+)/', '<a href="$1" target="_blank">$1</a>', $text); // Convert URLs to clickable links
    $text = preg_replace('/@(\w+)/', '<a href="http://www.twitter.com/$1" target="_blank">@$1</a>', $text); // Convert mentions to links
    $text = preg_replace('/#(\w+)/', '<a href="https://twitter.com/hashtag/$1" target="_blank">#$1</a>', $text); // Convert hashtags to links
    echo $text . "<br><br>";
}
?>
