<?php
function getIt($host,$user,$pass){

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://".$host.":8086/connectioncounts");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $user.":".$pass);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
    curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT_MS, 10000);
    $output = curl_exec($ch);
    $curl_errno = curl_errno($ch);
    $curl_error = curl_error($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);
    if ($curl_errno > 0) {
        echo "cURL Error ($curl_errno): $curl_error\n";
        return -1;
    }
    else
    {
        if(!$output)
        {
                echo "Error fetching XML";
        }
        else
        {
                $response_xml_data = $output;
                $repeaterxml = simplexml_load_string($response_xml_data);
                foreach ($repeaterxml->VHost as $vhost) {
                                //$inbytes = (string)$vhost->MessagesInBytesRate; // incoming rate
                                $outbytes = (string)$vhost->MessagesOutBytesRate; // outgoing rate
                                break;
                }
                $outbytes = ($outbytes / 131027)*1024; //Convert to kbits/s
                return $outbytes;
        }
    }
}
//example
$x = getIt("IP_ADDRESS_OR_HOSTNAME","USERNAME","PASSWORD"); // IP_ADDRESS_OR_HOSTNAME, USERNAME, PASSWORD OF WOWZA
?>
