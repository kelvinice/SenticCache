<?php
include 'connect.php';

$ch = curl_init(); 
$base_url = "https://sentic.net/api/";
$lang_code = "id";
$token = "s2QWG2p0lJaIQXGu";
$text = $_REQUEST["text"];

$data_key = md5($text);

$sql = "SELECT id, data_key, result FROM cache_table WHERE data_key = '".$data_key."'";
$result = $conn->query($sql);

//If data already exists
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo $row["result"];
        return;
    }
}else{
    $params = array('text' => $text);
    
    $request_url = $base_url ."/". $lang_code."/". $token . ".py?". http_build_query($params);

    $ch = curl_init(); 

    curl_setopt($ch, CURLOPT_URL, $request_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);

    $output = curl_exec($ch);

    curl_close($ch);

    $sql = "INSERT INTO cache_table(data_key, result) VALUES('".$data_key."', '".$output."')";
    $conn->query($sql);

    echo($output);
    return $output;
}



