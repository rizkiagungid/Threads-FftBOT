<?php
set_time_limit(0);

function request($url, $data = null, $headers = null, $outputheader = null)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
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


$headers = array();
$headers[] = 'Host: www.threads.net';
$headers[] = 'X-Ig-App-Id: 238260118697367';
$headers[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36 OPR/99.0.0.0';
$headers[] = 'X-Fb-Friendly-Name: BarcelonaPostLikersDialogQuery';
$headers[] = 'X-Fb-Lsd: RJ2eSzz0Tju308lXT_bGZ6';
$headers[] = 'Content-Type: application/x-www-form-urlencoded';
$headers[] = 'X-Asbd-Id: 129477';
$headers[] = 'Accept: */*';
$headers[] = 'Origin: https://www.threads.net';
$headers[] = 'Sec-Fetch-Site: same-origin';
$headers[] = 'Sec-Fetch-Mode: cors';
$headers[] = 'Sec-Fetch-Dest: empty';
$headers[] = 'Referer: https://www.threads.net/@itsciki';
$headers[] = 'Accept-Language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7';

$mediaID = "3141002295235099165";
$userID = "4340492104";

$getReplies = request('https://www.threads.net/api/graphql', 'lsd=RJ2eSzz0Tju308lXT_bGZ6&variables={"postID":"'.$mediaID.'"}&doc_id=5587632691339264', $headers, '');
echo $getReplies;


$getLikers = request('https://www.threads.net/api/graphql', 'lsd=RJ2eSzz0Tju308lXT_bGZ6&variables={"mediaID":"'.$mediaID.'"}&doc_id=9360915773983802', $headers, '');
echo $getLikers;

$getProfile = request('https://www.threads.net/api/graphql', 'lsd=RJ2eSzz0Tju308lXT_bGZ6&variables={"userID":"'.$userID.'"}&doc_id=23996318473300828', $headers, '');
echo $getProfile;

$getUserThreads = request('https://www.threads.net/api/graphql', 'lsd=RJ2eSzz0Tju308lXT_bGZ6&variables={"userID":"'.$userID.'"}&doc_id=6232751443445612', $headers, '');
echo $getUserThreads;

$getUserReplies = request('https://www.threads.net/api/graphql', 'lsd=RJ2eSzz0Tju308lXT_bGZ6&variables={"userID":"'.$userID.'"}&doc_id=6307072669391286', $headers, '');
echo $getUserReplies;

