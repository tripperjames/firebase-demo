<?php

namespace YOOtheme\Builder\Newsletter;

class CampaignMonitorProvider extends Provider
{
    protected $apiKey;

    protected $apiEndpoint = 'https://api.createsend.com/api/v3.1';

    /**
     * @param string $apiKey
     *
     * @throws \Exception
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
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
        $lists = [];
        if ($result = $this->get('clients.json') and $result['success']) {

            $clients = array_map(function ($client) {
                return ['value' => $client['ClientID'], 'text' => $client['Name']];
            }, $result['data']);

            if ($client_id = $provider['client_id'] ?: $clients[0]['value']) {

                if ($result = $this->get("/clients/$client_id/lists.json") and $result['success']) {
                    $lists = array_map(function ($client) {
                        return ['value' => $client['ListID'], 'text' => $client['Name']];
                    }, $result['data']);
                }
            }
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
            $name = (!empty($data['first_name']) ? $data['first_name'].' ' : '').$data['last_name'];
            $result = $this->post("subscribers/{$provider['list_id']}.json", [
                'EmailAddress' => $email,
                'Name' => $name,
                'Resubscribe' => true,
                'RestartSubscriptionBasedAutoresponders' => true,
            ]);

            if (!$result['success']) {
                throw new \Exception($result['data']);
            }

            return true;
        }
            throw new \Exception('No list selected.');

    }

    /**
     * Insert headers.
     *
     * @return array
     */
    protected function getHeaders()
    {
        return [
            'Authorization' => 'Basic '.base64_encode($this->apiKey.':nopass'),
        ];
    }

    /**
     * Get errormessage from response.
     *
     * @param $response
     * @param $formattedResponse
     *
     * @return string
     */
    protected function findError($response, $formattedResponse)
    {
        if (isset($formattedResponse['Message'])) {
            return sprintf('%d: %s', $formattedResponse['Code'], $formattedResponse['Message']);
        }
        return 'Unknown error, call getLastResponse() to find out what happened.';
    }
}
