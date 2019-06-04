<?php

namespace YOOtheme\Builder\Newsletter;

class MailChimpProvider extends Provider
{
    /**
     * @param string $apiKey
     *
     * @throws \Exception
     */
    public function __construct($apiKey)
    {
        if (strpos($apiKey, '-') === false) {
            throw new \Exception($this->app['trans']('Invalid API key.'));
        }

        list(, $dataCenter) = explode('-', $apiKey);

        $this->apiKey = $apiKey;
        $this->apiEndpoint = "https://{$dataCenter}.api.mailchimp.com/3.0";
    }

    /**
     * @param array $provider
     *
     * @throws \Exception
     *
     * @return array
     */
    public function lists($provider)
    {
        $clients = [];

        if ($result = $this->get('lists?count=100') and $result['success']) {
            $lists = array_map(function ($list) {
                return ['value' => $list['id'], 'text' => $list['name']];
            }, $result['data']['lists']);
        } else {
            throw new \Exception($result['data']);
        }

        return compact('lists', 'clients');
    }

    /**
     * @param array $email
     * @param array $data
     * @param array $provider
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function subscribe($email, $data, $provider)
    {
        if (isset($provider['list_id']) && $provider['list_id']) {

            $mergeFields = [];
            if (isset($data['first_name'])) {
                $mergeFields['FNAME'] = $data['first_name'];
            }
            if (isset($data['last_name'])) {
                $mergeFields['LNAME'] = $data['last_name'];
            }

            // Deprecated
            if (!isset($provider['double_optin'])) {
                $provider['double_optin'] = true;
            }

            $result = $this->post("lists/{$provider['list_id']}/members", [
                'email_address' => $email,
                'status' => $provider['double_optin'] ? 'pending' : 'subscribed',
                'merge_fields' => $mergeFields,
            ]);

            if (!$result['success']) {
                if (stripos($result['data'], 'already a list member') !== false) {
                    throw new \Exception(sprintf($this->app['trans']('%s is already a list member.'), htmlspecialchars($email)));
                } elseif (stripos($result['data'], 'was permanently deleted and cannot be re-imported. The contact must re-subscribe to get back on the list') !== false) {
                    throw new \Exception(sprintf($this->app['trans']('%s was permanently deleted and cannot be re-imported. The contact must re-subscribe to get back on the list.'), htmlspecialchars($email)));
                } elseif ($result['data'] === 'Please provide a valid email address.') {
                    throw new \Exception($this->app['trans']('Please provide a valid email address.'));
                }
                    throw new \Exception($result['data']);

            }

            return true;
        }
            throw new \Exception($this->app['trans']('No list selected.'));

    }

    /**
     * Insert headers.
     *
     * @return array
     */
    protected function getHeaders()
    {
        return [
            'Authorization' => 'apikey '.$this->apiKey,
        ];
    }
}
