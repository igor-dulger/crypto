<?php
/**
 * Created by PhpStorm.
 * User: Igor
 * Date: 02.03.2018
 * Time: 13:03
 */

define("BLOCK_SIZE", 16);

$cipher = "f20bdba6ff29eed7b046d1df9fb7000058b1ffb4210a580f748b4ac714c001bd4a61044426fb515dad3f21f18aa577c0bdf302936266926ff37dbf7035d5eeb4";


function convertToBlocks($data)
{
    $result = [];
    $size = strlen($data)/BLOCK_SIZE;
    for ($i=0; $i<$size; $i++) {
        $block = substr($data, $i*BLOCK_SIZE, BLOCK_SIZE);
        $result[] = substr($data, $i*BLOCK_SIZE, BLOCK_SIZE);
    }
    return $result;
}

function setPad($length)
{
//    echo "Pad ".$length."\n";
    $result = initExpectedBlock();
    for ($i=BLOCK_SIZE-1; $i>=0; $i--) {
        $result[$i] = ($i >= BLOCK_SIZE - $length) ? chr($length) : chr(0);
    }
    return $result;
}

function initExpectedBlock()
{
    $result = '';
    for($i=0; $i<BLOCK_SIZE; $i++) {
        $result .= chr(0);
    }
    return $result;
}

function makeGuess($message)
{
    $url = "http://crypto-class.appspot.com/po?er=";

    $handle = curl_init($url.$message);
    curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($handle);
    $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

    return ($httpCode == 404) ? true : false;
}

function solveBlock($iv, $data)
{
    $result = initExpectedBlock();
    for ($i = strlen($data)-1; $i>=0; $i--) {
        for ($guess=0; $guess<=255; $guess++) {
            if ($guess == BLOCK_SIZE - $i) {
                continue;
            }
            $result[$i] = chr($guess);
            $fake_iv = $iv ^ $result ^ setPad(BLOCK_SIZE - $i);
            if (makeGuess(bin2hex($fake_iv.$data))) {
                break;
            }
            if ($guess == 255) $result[$i] = chr(BLOCK_SIZE - $i);
        }
    }
    return $result;
}

function solveMessage($message)
{
    $result = '';
    $blocks = convertToBlocks(hex2bin($message));
    for ($i=count($blocks)-1; $i>0; $i--) {
        $result = solveBlock($blocks[$i-1], $blocks[$i]) . $result;
    }
    return bin2hex($result);
}
$message = solveMessage($cipher);
print $message."\n";
print hex2bin($message)."\n";
