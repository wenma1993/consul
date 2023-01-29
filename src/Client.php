<?php
namespace Wenwen1993\Consul;

abstract class Client
{

     /**
     * Send a HTTP request.
     */
    protected function request(string $method, string $url, array $options = []): ConsulResponse
    {
        $this->logger->debug(sprintf('Consul Request [%s] %s', strtoupper($method), $url));
        try {
            // Create a HTTP Client by $clientFactory closure.
            $client = new \GuzzleHttp\Client();
    
            $response = $client->request($method, $url, $options);
        } catch (TransferException $e) {
            $message = sprintf('Something went wrong when calling consul (%s).', $e->getMessage());
            $this->logger->error($message);
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }

        if ($response->getStatusCode() >= 400) {
            $message = sprintf('Something went wrong when calling consul (%s - %s).', $response->getStatusCode(), $response->getReasonPhrase());
            $this->logger->error($message);
            $message .= PHP_EOL . (string) $response->getBody();
            if ($response->getStatusCode() >= 500) {
                throw new ServerException($message, $response->getStatusCode());
            }
            throw new ClientException($message, $response->getStatusCode());
        }

        return new ConsulResponse($response);
    }

}