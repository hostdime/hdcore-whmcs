<?php

class CoreVpsClient
{
    private $_endpoint = 'https://api.hostdime.com/v1';

    public function __construct($publicKey, $privateKey, $options=NULL)
    {
        $this->_publicKey = $publicKey;
        $this->_privateKey = $privateKey;

        if ($options !== NULL)
        {
            if (isset($options['endpoint']))
                $this->_endpoint = $options['endpoint'];
        }
    }

    public function call($action, $parameters=NULL)
    {
        if ($parameters === NULL)
            $parameters = array();

        $timestamp = self::generateTimestamp();
        $unique = self::generateUuid();
        $hash = self::generateHash($timestamp, $unique, $this->_privateKey, $action, $parameters);
        $route = self::generateRoute($action);

        $parameters = array_merge($parameters, array(
            'api_key'       => $this->_publicKey,
            'api_timestamp' => $timestamp,
            'api_unique'    => $unique,
            'api_hash'      => $hash
        ));

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, "{$this->_endpoint}/{$route}");
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($parameters));

        $result = curl_exec($curl);
        $result = json_decode($result, true);

        if(curl_errno($curl))
        {
            $result = array(
                'error' => array(
                    'code' => curl_errno($curl),
                    'message' => curl_error($curl)
                )
            );
        }

        curl_close($curl);

        return $result;
    }

    private static function generateTimestamp()
    {
        return time();
    }

    private static function generateRoute($action)
    {
        return sprintf('%s.json', implode('/', explode('.', $action)));
    }

    private static function generateUuid()
    {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0x0fff ) | 0x4000,
            mt_rand( 0, 0x3fff ) | 0x8000,
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }

    private static function generateHash($timestamp, $unique, $key, $action, $parameters)
    {
      $elements = array(
        $timestamp,
        $unique,
        $key,
        $action,
        json_encode(json_encode($parameters, JSON_FORCE_OBJECT))
      );
      $string = implode(':',$elements);
      $hash = hash("sha256", $string);

      return $hash;
    }
}
