<?php
/**
 * Created by PhpStorm.
 * User: Igor
 * Date: 05.03.2018
 * Time: 15:43
 */

define("BLOCK_SIZE", 1024);

$birthday_hash = '03c08f4ee0b576fe319338139c045c89c3e8e9409633bea29442e21425006ea8';
$birthday_file = '6.2.birthday.mp4_download';

$test_file = 'test11223344';
$test_hashes = [
    'd8f8a9eadd284c4dbd94af448fefb24940251e75ca2943df31f7cfbb6a4f97ed',
    '26949e3320c315f179e2dfc95a4158dcf9a9f6ebf3dfc69252cd83ad274eeafa',
    '946e42c2bd9cbb56dcbefe0eea7ad361e18a4a052421b088b8050b1ba99795ff',
    'af7aca38c840da949c02a57e1c31d48ab7a1b9c7486638a892f2409770ec3ae5'
];

$intro_file = '6.1.intro.mp4_download';



function encode($filename)
{
    $input = fopen($filename, "rb");
    file_put_contents($filename.'_result', '');
    $blocks = ceil(filesize($filename) / BLOCK_SIZE);
    $hash = '';
    for ($i=$blocks-1; $i >= 0; $i--) {
        fseek($input, $i*BLOCK_SIZE);
        $block = fread($input, BLOCK_SIZE);
//        save_result($filename,$block.$hash);
//        echo $i. ' s->'. strlen($block) ." h->". bin2hex($hash). " Rs->" . strlen($block.$hash) . "\n";
        $hash = hex2bin(hash('sha256', $block.$hash));
//        echo $i. " nh->". bin2hex($hash) . "\n";
    }
    fclose($input);
    return $hash;
}

function decode($filename, $hash)
{
    echo "File size " .filesize($filename)."\n";
    $input = fopen($filename, "rb");
    $original = fopen($filename."_original", "a");
    $i = 0;
    while (!feof($input)) {
        $block = fread($input, 1024+32);
//        echo bin2hex($block)."\n";
        if ($hash != hash('sha256', $block)) {
            echo "$i Error incorrect hash ".hash('sha256', $block). " Expected $hash\n";
            return false;
        }

        $hash = bin2hex(substr($block, BLOCK_SIZE, 32));
        fwrite($original, substr($block, 0,BLOCK_SIZE));
//echo $hash."\n";
        $i++;
    }
    fclose($input);
    fclose($original);
    return true;
}

function save_result($filename, $data)
{
    $result_name = $filename.'_result';
    $old_data = file_get_contents($result_name);
    file_put_contents($result_name, $data . $old_data);
//echo " Filesize" . strlen($data . $old_data). "\n";
}

function check($file, $expected_hash)
{
    $data_dir = "data/";
    $hash = encode($data_dir.$file);
    echo "Blocks " . ceil(filesize($data_dir.$file)/ BLOCK_SIZE) . "\n";
    echo "Expected " .$expected_hash."\n";
    echo "Actual " . bin2hex($hash). "\n";
    echo (bin2hex($hash) == $expected_hash) ? "Good job!!\n" : "Fail, trace it dude\n";
    $decode = decode($data_dir.$file."_result", bin2hex($hash));
    echo ($decode) ? "Decoded\n" : "Decode error\n";

}
$hash = encode("data/".$intro_file);
echo bin2hex($hash);

//check($birthday_file, $birthday_hash);
