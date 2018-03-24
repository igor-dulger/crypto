<?php
/**
 * Created by PhpStorm.
 * User: Igor
 * Date: 02.03.2018
 * Time: 13:03
 */
$ciphers = [
    [
        "key" => '140b41b22a29beb4061bda66b6747e14',
        "cipher" => '4ca00ff4c898d61e1edbf1800618fb2828a226d160dad07883d04e008a7897ee2e4b7465d5290d0c0e6c6822236e1daafb94ffe0c5da05d9476be028ad7c1d81'
    ],
    [
        "key" => '140b41b22a29beb4061bda66b6747e14',
        "cipher" => '5b68629feb8606f9a6667670b75b38a5b4832d0f26e1ab7da33249de7d4afc48e713ac646ace36e872ad5fb8a512428a6e21364b0c374df45503473c5242a253'
    ],
    [
        "key" => '36f18357be4dbd77f050515c73fcf9f2',
        "cipher" => '69dda8455c7dd4254bf353b773304eec0ec7702330098ce7f7520d1cbbb20fc388d1b0adb5054dbd7370849dbf0b88d393f252e764f1f5f7ad97ef79d59ce29f5f51eeca32eabedd9afa9329'
    ],
    [
        "key" => '36f18357be4dbd77f050515c73fcf9f2',
        "cipher" => '770b80259ec33beb2561358a9f2dc617e46218c0a53cbeca695ae45faa8952aa0e311bde9d4e01726d3184c34451'
    ],
];

function convertToBlocks($data)
{
    $result = [];
    $size = strlen($data)/16;
    for ($i=0; $i<$size; $i++) {
        $block = substr($data, $i*16, 16);
        $result[] = substr($data, $i*16, 16);
    }
    return $result;
}

function aes_cbc_decrypt($key, $cipher)
{
    $key = hex2bin($key);
    $messages = [];
    $blocks = convertToBlocks(hex2bin($cipher));

    for ($i=1; $i<count($blocks); $i++) {
        $messages[] = mcrypt_decrypt ( 'rijndael-128', $key, $blocks[$i] , 'ecb') ^ $blocks[$i-1];
    }
    return implode('', $messages);
}

function aes_ctr_decrypt($key, $cipher)
{
    $key = hex2bin($key);
    $messages = [];
    $blocks = convertToBlocks(hex2bin($cipher));
    $IV = array_shift($blocks);
    for ($i=0; $i<count($blocks); $i++) {
        $messages[] = mcrypt_encrypt ( 'rijndael-128', $key, $IV, 'ecb') ^ $blocks[$i];
        $IV = inc_IV($IV);
    }
    return implode('', $messages);
}

function inc_IV($IV)
{
    $result = $IV;
    $overload = false;
    $j=0;
    do {
        $current_index = strlen($IV)-1-$j;
        $result[$current_index]  = chr(ord($IV[$current_index]) + 1);
        if (ord($IV[$current_index]) > ord($result[$current_index])) {
            $overload = true;
            $j++;
        }
    } while ($overload);
    return $result;
}

echo aes_cbc_decrypt($ciphers[0]['key'], $ciphers[0]['cipher']), "\n";
echo aes_cbc_decrypt($ciphers[1]['key'], $ciphers[1]['cipher']), "\n";
echo aes_ctr_decrypt($ciphers[2]['key'], $ciphers[2]['cipher']), "\n";
echo aes_ctr_decrypt($ciphers[3]['key'], $ciphers[3]['cipher']), "\n";
