<?php namespace Tjphippen\Hart;

use GuzzleHttp\Client;

/**
 * This is the main class for Hart.
 * Interaction with this class is typically done via the Hart facade.
 *
 * @license MIT
 * @package Tjphippen\Hart
 */

class Hart
{
    private $config;
    private $client;
    public $xml;

    /**
     * Create Guzzle client from configuration parameters.
     */
    function __construct($config)
    {
        $this->config = $config;
        $this->client = new Client;
    }

    /**
     * Access previously generated report by Token.
     */
    public function getByToken($token)
    {
        $this->config['pass'] = 109;
        $body = $this->toLineBody(array_merge($this->config, ['token' => $token]));
        $request = $this->client->post($this->getBaseUrl(), ['body' => $body]);
        $this->xml = simplexml_load_string($request->getBody()->getContents());
        return $this;
    }

    /**
     * Run full report w/object or array of a person's details.
     */
    public function getCredit($person)
    {
        $keys = ['name', 'address', 'city', 'ssn', 'city', 'state', 'zip', 'dob'];
        $person = (is_object($person) ? $person->toArray() : $person);
        $person = array_filter(array_intersect_key($person, array_flip($keys)));
        $body = $this->toLineBody(array_merge($this->config, $person));
        $request = $this->client->post($this->getBaseUrl(), ['body' => $body]);
        $this->xml = simplexml_load_string($request->getBody()->getContents());
        return $this;
    }

    /**
     * Parse XML object to array including transaction, token, score and reasons.
     */
    public function parse()
    {
        $credit['transaction'] = current($this->xml->HX5_transaction_information->Transid);
        $credit['token'] = current($this->xml->HX5_transaction_information->Token);
        $beacon = $this->xml->bureau_xml_data->{$this->config['bureau'] . '_Report'}->subject_segments->beacon;
        $credit['score'] = current($beacon->score) + 0;
        if(!$credit['score']){
            $credit['reasons'][] = end($beacon->reject_message_code);
        }else{
            foreach(current($beacon->reason_codes) as $reason){
                $credit['reasons'][] = $reason;
            }
        }
        return $credit;
    }

    /**
     * Provide Guzzle the base endpoint for production or testing requests.
     */
    public function getBaseUrl()
    {
        if($this->config['env'] == 'production'){
            return 'https://www.creditsystem.com/cgi-bin/pccreditxml';
        }else{
            return 'https://www.testdatasolutions.com/xmlgw';
        }
    }

    /**
     * Format config and query parameters to proper request body format.
     */
    public function toLineBody($array)
    {
        unset($array['env']);
        $dataAttributes = array_map(function($value, $key) {
            return $key.'='.$value;
        }, array_values($array), array_keys($array));
        return implode("\n", $dataAttributes);
    }

}