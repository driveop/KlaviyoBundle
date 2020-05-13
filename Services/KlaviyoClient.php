<?php

namespace KlaviyoBundle\Services;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use Psr\Log\LoggerInterface;

class KlaviyoClient
{

    protected $api_key;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var logInterface
     */
    private $log;

    public function __construct(LoggerInterface $log, $klaviyo_api_key)
    {
        $this->api_key = $klaviyo_api_key;
        $this->log = $log;
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => 'https://a.klaviyo.com/api/',
            //'handler' => $handlerStack,
            /*'headers' => [
                'User-Agent' => $ua
            ]*/
        ]);
    }


    #########################
    ##        LISTS        ##
    #########################

    /**
     * CREATE A LIST
     * @param $listName
     * @return bool|int
     */
    public function createList($listName) {

        $headers = [
            'Content-Type' => 'application/json'
        ];

        $body = json_encode([
            'api_key' => $this->api_key,
            'list_name' => $listName
        ]);

        try {
            $request = new Request('POST', 'v2/lists', $headers, $body);
            $response = $this->client->send($request);
        } catch (\Throwable $e) {
            $this->log->error('KlaviyoAPI -> createList ' . $e->getMessage());
            return false;
        }

        return $response->getStatusCode();
    }

    #########################
    ## LIST SUBSCRIPTIONS  ##
    #########################

    /**
     * SUBSCRIBE TO A LIST
     * @param $listId
     * @param $emails
     * @return bool|int
     */
    public function subscribeToList($listId, $emails)
    {
        $profiles = [];
        if (is_array($emails)) {
            foreach ($emails as $email)
            {
                array_push($profiles, ['email' => $email]);
            }
        } else if (is_string($emails))
            $profiles = [['email' => $emails]];

        $headers = [
            'Content-Type' => 'application/json'
        ];

        $body = json_encode([
            'api_key' => $this->api_key,
            'profiles' => $profiles
        ]);

        try {
            $request = new Request('POST', 'v2/list/'. trim($listId) .'/subscribe', $headers, $body);
            $response = $this->client->send($request);
        } catch (\Throwable $e) {
            $this->log->error('KlaviyoAPI -> subscribeToList ' . $e->getMessage());
            return false;
        }

        return $response->getStatusCode();
    }


    /**
     * UNSUBSCRIBE TO A LIST
     * @param $listId
     * @param $emails
     * @return bool|int
     */
    public function unsubscribeFromList($listId, $emails)
    {

        $profiles = [];
        if (is_array($emails)) {
            foreach ($emails as $email)
            {
                array_push($profiles, $email);
            }
        } else if (is_string($emails))
            $profiles = [$emails];

        $headers = [
            'Content-Type' => 'application/json'
        ];

        $body = json_encode([
            'api_key' => $this->api_key,
            'emails' => $profiles
        ]);

        try {
            $request = new Request('DELETE', 'v2/list/'. trim($listId) .'/unsubscribe', $headers, $body);

            $response = $this->client->send($request);
        } catch (\Throwable $e) {
            $this->log->error('KlaviyoAPI -> subscribeToList ' . $e->getMessage());
            return false;
        }

        return $response->getStatusCode();
    }

}