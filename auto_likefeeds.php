<?php
set_time_limit(0);
$uuid = sprintf(
    '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
    mt_rand(0, 0xffff),
    mt_rand(0, 0xffff),
    mt_rand(0, 0xffff),
    mt_rand(0, 0x0fff) | 0x4000,
    mt_rand(0, 0x3fff) | 0x8000,
    mt_rand(0, 0xffff),
    mt_rand(0, 0xffff),
    mt_rand(0, 0xffff)
);


function request($url, $data = null, $headers = null, $outputheader = null)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    if($data):
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    endif;
    if($outputheader):
      curl_setopt($ch, CURLOPT_HEADER, 1);
    endif;
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if($headers):
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    endif;

   // curl_setopt($ch, CURLOPT_ENCODING, "GZIP");
    return curl_exec($ch);
}


$token = file_get_contents('token.txt');



$headers = array();
$headers[] = 'X-Bloks-Version-Id: 5f56efad68e1edec7801f630b5c122704ec5378adbee6609a448f105f34a9c73';
$headers[] = 'X-Ig-Www-Claim: hmac.AR2jr3_r-N6PqPM09G7tetqnPfD9P_Ux_HFjJvwyPwksRLqR';
$headers[] = 'X-Ig-Device-Id: '.$uuid;
$headers[] = 'X-Ig-Android-Id: android-6be35fa278d92525';
$headers[] = 'User-Agent: Barcelona 289.0.0.77.109 Android (31/12; 440dpi; 1080x2148; Google/google; sdk_gphone64_arm64; emulator64_arm64; ranchu; en_US; 489720145)';
$headers[] = 'Accept-Language: en-US';
$headers[] = 'Authorization: Bearer '.$token;
$headers[] = 'Host: i.instagram.com';





$getFeeds = request('https://i.instagram.com/api/v1/feed/text_post_app_timeline/', 'feed_view_info=[]&max_id=&pagination_source=text_post_feed_threads&is_pull_to_refresh=0&_uuid='.$uuid.'&bloks_versioning_id=5f56efad68e1edec7801f630b5c122704ec5378adbee6609a448f105f34a9c73', $headers, '');
$parsegetFeeds = json_decode($getFeeds, TRUE);

if(isset($parsegetFeeds['message']) == "login_required"){
    echo "Auth expired, please get token again.";
    exit();
}



foreach ($parsegetFeeds['items'] as $postingan) {
    $mediaid = $postingan['thread_items'][0]['post']['id'];
    echo "Target Post: ".$mediaid . PHP_EOL;
    
    $gaslike = request('https://i.instagram.com/api/v1/media/'.$mediaid.'/like/', 'signed_body=SIGNATURE.%7B%22delivery_class%22%3A%22organic%22%2C%22tap_source%22%3A%22button%22%2C%22media_id%22%3A%22'.$mediaid.'%22%2C%22radio_type%22%3A%22wifi-none%22%2C_uuid%22%3A%22'.$uuid.'%2C%22recs_ix%22%3A%221%22%2C%22is_carousel_bumped_post%22%3A%22false%22%2C%22container_module%22%3A%22ig_text_feed_timeline%22%2C%22feed_position%22%3A%221%22%7D&d=0', $headers, '');
    echo $gaslike; // status "ok" or "fail"
}
