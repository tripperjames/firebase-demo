<?php

namespace YOOtheme\Builder\Newsletter;

use YOOtheme\ContainerTrait;

abstract class Provider
{
    use ContainerTrait;

    protected $apiKey;

    protected $apiEndpoint = '';

    protected $inject = [
        'http' => 'app.http',
    ];

    protected function getHeaders()
    {
        return [];
    }

    public function __call($name, $arguments)
    {
        $method = $arguments[0];
        $args = isset($arguments[1]) ? $arguments[1] : [];
        $url = $this->apiEndpoint.'/'.$method;

        $headers = array_merge([
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json',
        ], $this->getHeaders());

        switch ($name) {
            case 'post':

                $response = $this->http->post($url, json_encode($args), compact('headers'));
                break;

            case 'get':

                $query = http_build_query($args, '', '&');
                $response = $this->http->get($url.'?'.$query, compact('headers'));
                break;

            default:
                throw new \Exception('Call to undefined method '.$name);
        }

        $encoded = json_decode($response->getBody(), true);

        if ($response->getStatusCode() >= 200 && $response->getStatusCode() <= 299 && $encoded) {
            return [
                'success' => true,
                'data' => $encoded,
            ];
        }
            return [
                'success' => false,
                'data' => $this->findError($response, $encoded),
            ];

    }

    protected function findError($response, $formattedResponse)
    {
        if (isset($formattedResponse['detail'])) {
            return $formattedResponse['detail'];
        }

        return 'Unknown error, call getLastResponse() to find out what happened.';
    }
}
