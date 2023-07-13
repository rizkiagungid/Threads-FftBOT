<?php
//cronjob this
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

$token = file_get_contents('token.txt'); //CHANGE


$headers = array();
$headers[] = 'X-Bloks-Version-Id: 5f56efad68e1edec7801f630b5c122704ec5378adbee6609a448f105f34a9c73';
$headers[] = 'X-Ig-Www-Claim: hmac.AR2jr3_r-N6PqPM09G7tetqnPfD9P_Ux_HFjJvwyPwksRLqR';
$headers[] = 'X-Ig-Device-Id: '.$uuid;
$headers[] = 'X-Ig-Android-Id: android-6be35fa278d92525';
$headers[] = 'User-Agent: Barcelona 289.0.0.77.109 Android (31/12; 440dpi; 1080x2148; Google/google; sdk_gphone64_arm64; emulator64_arm64; ranchu; en_US; 489720145)';
$headers[] = 'Accept-Language: en-US';
$headers[] = 'Authorization: Bearer '.$token; 
$headers[] = 'Host: i.instagram.com';


$getNotif = request('https://i.instagram.com/api/v1/text_feed/text_app_notifications/?feed_type=all&mark_as_seen=false&timezone_offset=25200&timezone_name=Asia%2FJakarta', '', $headers, '');
$parsegetNotif = json_decode($getNotif, TRUE);

if(isset($parsegetNotif['message']) == "login_required"){
    echo "Auth expired, please get token again.";
    exit();
}


// kirim POST /api/v1/text_feed/text_app_inbox_seen/ optional
// POST /api/v1/friendships/destroy/xxxx/ unfollow
//echo $parsegetNotif['new_stories']
//echo $parsegetNotif['old_stories']

foreach ($parsegetNotif['new_stories'] as $story) {
    $userId = $story['args']['inline_follow']['user_info']['id'];
    $username = $story['args']['inline_follow']['user_info']['username'];
    echo "New Follow: ${username} ".$userId . PHP_EOL;
    //gas kirim request follow
    $resultngefollow = json_decode(request('https://i.instagram.com/api/v1/friendships/create/'.$userId.'/', 'signed_body=SIGNATURE.%7B%22user_id%22%3A%22'.$userId.'%22%2C%22radio_type%22%3A%22wifi-none%22%2C%22_uid%22%3A%2260438147690%22%2C%22device_id%22%3A%22android-6be35fa278d92525%22%2C%22_uuid%22%3A%22'.$uuid.'%22%7D', $headers, ''), TRUE);
    
    $following = $resultngefollow['friendship_status']['following'];
    $followedBy = $resultngefollow['friendship_status']['followed_by'];

echo "Following: " . ($following ? 'true' : 'false') . PHP_EOL;
echo "Followed by: " . ($followedBy ? 'true' : 'false') . PHP_EOL;
}

