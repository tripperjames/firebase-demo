<?php

namespace YOOtheme\Builder\Newsletter;

use YOOtheme\ContainerTrait;

class NewsletterController
{
    use ContainerTrait;

    public $providers;

    public $namespace = __NAMESPACE__;

    public function __construct()
    {
        $this->providers = [
            'mailchimp' => "{$this->namespace}\\MailChimpProvider",
            'cmonitor' => "{$this->namespace}\\CampaignMonitorProvider",
        ];
    }

    public function lists($response, $settings)
    {
        if (!isset($settings['name']) or !$provider = $this->providers[$settings['name']]) {
            return $response->withJson('Invalid provider', 400);
        }

        $apiKey = $this->theme->config[$settings['name'].'_api'];

        try {
            $return = (new $provider($apiKey))->lists($settings);
        } catch (\Exception $e) {
            return $response->withJson($e->getMessage(), 400);
        }

        return $response->withJson($return);
    }

    public function subscribe($response, $settings, $email, $first_name = '', $last_name = '')
    {
        $settings = $this->app['encryption']->decrypt($settings);

        if (!isset($settings['name']) or !$provider = $this->providers[$settings['name']]) {
            return $response->withJson('Invalid provider', 400);
        }

        $apiKey = $this->theme->config[$settings['name'].'_api'];

        try {
            (new $provider($apiKey))->subscribe($email, compact('first_name', 'last_name'), $settings);
        } catch (\Exception $e) {
            return $response->withJson($e->getMessage(), 400);
        }

        $return = [
            'successful' => true,
        ];

        if ($settings['after_submit'] === 'redirect') {
            $return['redirect'] = $settings['redirect'];
        } else {
            $return['message'] = $this->app['trans']($settings['message']);
        }

        return $response->withJson($return);
    }
}
