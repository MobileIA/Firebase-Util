<?php

namespace MobileIA\Firebase;

use Zend\Http\Request;
use Zend\Http\Client;
use Zend\Stdlib\Parameters;
use Zend\Json\Json;

/**
 * Description of Helper
 *
 * @author matiascamiletti
 */
class FirebaseHelper 
{
    /**
     * Almacena la URL base de la API.
     */
    const BASE_URL = 'https://fcm.googleapis.com/fcm/send';
    /**
     * Almacena el KEY para conectarse
     * @var string
     */
    protected $apiKey = '';
    /**
     * 
     * @param string $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }
    /**
     * 
     * @param array $ids
     * @param array $data
     * @return array
     */
    public function sendToRegistrationsIds($ids, $data)
    {
        // Creamos la peticion con los parametros necesarios
        $request = $this->generateRequest(array(
            'registration_ids' => $ids,
            'data' => $data
        ));
        // Ejecutamos la petición
        $response = $this->dispatchRequest($request);

        return $response;
    }
    /**
     * 
     * @param string $topicName
     * @param array $params
     * @return array
     */
    public function sendTopic($topicName, $params)
    {
        // Creamos la peticion con los parametros necesarios
        $request = $this->generateRequest(array(
            'to' => '/topics/'.$topicName,
            'data' => $params
        ));
        // Ejecutamos la petición
        $response = $this->dispatchRequest($request);
        
        return $response;
    }
    /**
     * Realiza la peticion y devuelve los parametros
     * @param Request $request
     * @return array
     */
    protected function dispatchRequest($request)
    {
        $client = new Client();
        $response = $client->dispatch($request);
        return Json::decode($response->getBody());
    }
    /**
     * Genera un request con el path y los parametros
     * @param string $path
     * @param array $params
     * @return Request
     */
    protected function generateRequest($params)
    {
        $request = new Request();
        $request->getHeaders()->addHeaders(array(
            'Content-Type' => 'application/json',
            'Authorization' => 'key=' . $this->apiKey
        ));
        $request->setUri(self::BASE_URL);
        $request->setMethod(Request::METHOD_POST);
        $request->setContent(Json::encode($params));
        $request->setPost(new Parameters($params));
        
        return $request;
    }
}