<?php

namespace App\Utils;

class BlockCypherConfig {
    const ETH_ADDRESSAPI_URL = 'https://api.blockcypher.com/v1/eth/main/addrs';
    const ETH_TRANSACTION_URL = 'https://api.blockcypher.com/v1/eth/main/txs';
    const ETH_SIGNER_PATH = __DIR__ . '/signer';
    const BLOCKCYPHER_TOKEN = 'db66a22e72ee4f8cacf4001f1b55eb3a';
}


class BlockCypher {
    const MY_TOKEN = 'db66a22e72ee4f8cacf4001f1b55eb3a';

    public static function createWallet ($currencySymbol) {
        switch ($currencySymbol) {
        case 'eth':
            $url = "https://api.blockcypher.com/v1/eth/main/addrs?token=" . self::MY_TOKEN;
            break;
        case 'btc':
            $url = "https://api.blockcypher.com/v1/btc/main/addrs?token=" . self::MY_TOKEN;
            break;
        case 'dash':
            $url = "https://api.blockcypher.com/v1/dash/main/addrs?token=" . self::MY_TOKEN;
            break;
        default:
            throw new Exception('钱包类型错误');
            break;
        }
        $resJson = Service::curlRequest($url, 'post');
        $resArr = json_decode($resJson, true);
        if ($resArr == null) {
            throw new Exception('调用新建钱包接口失败');
        }
        if (isset($resArr['errors'])) {
            throw new Exception(json_encode($resArr['errors']));
        }

        return $resArr;
    }

    public static function getBalance ($currencySymbol, $address) {
        switch ($currencySymbol) {
            case 'eth':
                $url = "https://api.blockcypher.com/v1/eth/main/addrs/$address/balance";
                break;
            case 'btc':
                $url = "https://api.blockcypher.com/v1/btc/main/addrs/$address/balance";
                break;
            case 'dash':
                $url = "https://api.blockcypher.com/v1/dash/main/addrs/$address/balance";
                break;
            default:
                throw new Exception('货币符号错误');
                break;
        }
        $resJson = Service::curlRequest($url);
        $resArr = json_decode($resJson, true);
        if ($resArr == null) {
            return false;
        }
        if (isset($resArr['errors'])) {
            throw new Exception(json_encode($resArr['errors']));
        }
        $balance = $resArr['balance'];

        return $balance;
    }

    public static function makeTransaction ($currencySymbol, $inputAddress, $privateKey, $outputAddress, $amount) {
        switch ($currencySymbol) {
            case 'eth':
                $newUrl = "https://api.blockcypher.com/v1/eth/main/txs/new?token=" . self::MY_TOKEN;
                $sendUrl = "https://api.blockcypher.com/v1/eth/main/txs/send?token=" . self::MY_TOKEN;
                break;
            case 'btc':
            case 'dash':
                $newUrl = 'https://api.blockcypher.com/v1/bcy/test/txs/new';
                $sendUrl = "https://api.blockcypher.com/v1/bcy/test/txs/send?token=" . self::MY_TOKEN;
                break;
            default:
                throw new Exception('货币符号错误');
                break;
        }

        // 创建交易
        $data = array(
            'inputs' => array(array(
                'addresses' => array($inputAddress)
            )),
            'outputs' => array(array(
                'addresses' => array($outputAddress),
                'value' => $amount
            )),
        );
        $data = json_encode($data);
        $resJson = Service::curlRequest($newUrl, 'post', $data);
        echo "$resJson\n";
        $resArr = json_decode($resJson, true);
        if ($resArr == null) {
            return false;
        }
        if (isset($resArr['errors'])) {
            throw new Exception(json_encode($resArr['errors']));
        }
        $sendData = array(
            'tx' => $resArr['tx'],
            'tosign' => $resArr['tosign'],
        );
        $tosign = $resArr['tosign'][0];

        // 签名
        $command = BlockCypherConfig::ETH_SIGNER_PATH
                . ' ' . escapeshellarg($tosign)
                . ' ' . escapeshellarg($privateKey);
        $ret = exec($command, $out, $status);
        if ($status == -1) return false;
        $signature = $out[0];
        $sendData['signatures'] = array($signature);

        // 确认交易
        $sendData = json_encode($sendData);
        $resJson = Service::curlRequest($sendUrl, 'post', $sendData);
        echo "$resJson\n";
        $resArr = json_decode($resJson, true);
        if ($resArr == null) {
            return false;
        }
        if (isset($resArr['errors'])) {
            throw new Exception(json_encode($resArr['errors']));
        }

        $fees = $resArr['fees'];
        return $fees;
    }
}

//$result = BlockCypherUtil::createWallet('eth');

//$result = BlockCypherUtil::makeTransaction('eth', 'f5b40d476323c27f0a56c51ddf965cf156a1efd5', '626d2a3d36cc64a1da73372dfdbbf259c5087a64a88871352691dc44c3acbee3', '6d69ab38a941f38a09a898bbecab3fffc7aba9ca', 1);
//array(3) {
  //["private"]=>
  //string(64) "9e34a6cab01156e58c9451d0f873a07aca4743b4ab66a7a35a4290dfd283c581"
  //["public"]=>
  //string(130) "0406518faa7dc07bd694ed20b88a86bc9e56105c16119bfce4365e265b69ba16b1a6340f27de1dcfd41f473a3eaba0a9308df8d699d70e47ce157fb74ea047722f"
  //["address"]=>
  //string(40) "3b7ea3d050786ab4b334a321867c3e7fa90eb4a3"
//}
//array(3) {
  //["private"]=>
  //string(64) "7400f86a314d9f19c38f1131b898f46448b642b7290b4872f3533412a435cbd8"
  //["public"]=>
  //string(130) "04ef521792353631b678f42a169ce90419190e287ef90e6edb4ac295f65b4eb21b2269752ff567a9f6065f36f3f0536a2eca04cd0fe76dbf52a49e5eb2012dfc87"
  //["address"]=>
  //string(40) "11c632c5eff520669160c0d57b0c85726b488adc"
//}
//$result = BlockCypherUtil::getWalletBalance('ETH', '11c632c5eff520669160c0d57b0c85726b488adc');

//curl -sd '{"inputs":[{"addresses":["3b7ea3d050786ab4b334a321867c3e7fa90eb4a3"]}],"outputs":[{"addresses":["11c632c5eff520669160c0d57b0c85726b488adc"]}],"value":1}' https://api.blockcypher.com/v1/eth/main/txs/new\?token\=db66a22e72ee4f8cacf4001f1b55eb
//
//===========================================btc==================================
//$result = BlockCypherUtil::createWallet('btc');
//array(4) {
  //["private"]=>
  //string(64) "9cf6c594f63111908694619da88e04a3450efb1a503d2b7f03bf8b43d6b82316"
  //["public"]=>
  //string(66) "0205007a429bf556738586ec41a3e2144c1573952501fbcc2ae0a367532361c6e3"
  //["address"]=>
  //string(33) "1WaRUehxnH4U7BrZmYhUpf7QYRVkqENDE"
  //["wif"]=>
  //string(52) "L2UpzdfDqya4w8WRUBWAVuUMiM3nfsPaicoNQAEcYEV11m4eD8U8"
//}
//array(4) {
  //["private"]=>
  //string(64) "c484df4dbfd55d30251d8cb643823d059ee0809edc3520720c5e078b57e15fd2"
  //["public"]=>
  //string(66) "033ed11f43b4bc5fc6d7b7edeb10263395aca61678efe2b54b6d983d99fe7c31c6"
  //["address"]=>
  //string(34) "12KkgCrkq9m74yH9BCC6etY661gN6Z78AJ"
  //["wif"]=>
  //string(52) "L3oicH8LgqQ6qtMrkrzK4F8UPWao28MTL1mh1hz1ijiwYq1mYU1M"
//}
//
//$result = BlockCypherUtil::makeTransaction('btc', '1WaRUehxnH4U7BrZmYhUpf7QYRVkqENDE', '9cf6c594f63111908694619da88e04a3450efb1a503d2b7f03bf8b43d6b82316', '12KkgCrkq9m74yH9BCC6etY661gN6Z78AJ', 1);

//var_dump($result);

//==============================================dash==================================
//{
  //"private": "41f39a88f9c39ad08b92c84c28e504807cd14cc17be884bdff6db32529b57e32",
  //"public": "035def487521e9b1b3b5b55e601a3386f7899018fe704460ea02c4183d4f2df8be",
  //"address": "Xrk3GEBd3V3ptp5R1H5TxauWT522HBnrke",
  //"wif": "XDVqJvE56kLT4erqwnAKTA6484KjKE8bpiDvC2rXf9WY5RhrfK7q"
//}
