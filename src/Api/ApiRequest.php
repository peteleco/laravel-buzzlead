<?php namespace Peteleco\Buzzlead\Api;

use GuzzleHttp\Client;
use Peteleco\Buzzlead\Api\Response\ApiResponse;
use Peteleco\Buzzlead\UndefinedUrlPropertyException;
use Psr\Http\Message\ResponseInterface;

abstract class ApiRequest
{

    /**
     * @var string
     */
    protected $env;

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var string
     */
    protected $path;

    /**
     * Url param {emailUser}
     *
     * @var string
     */
    protected $emailUser;

    /**
     * @var string
     */
    protected $apiToken;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var Client
     */
    protected $client;

    public function __construct(array $config = [])
    {

        $this->setEnv($config['env'])
             ->setEmailUser($config['api']['email'])
             ->setApiKey($config['api']['key'])
             ->setApiToken($config['api']['token'])
             ->setBaseUrl($config['urls'][$this->env]);

        // Setup guzzle client
        $this->client = new Client([
            'base_uri' => $this->baseUrl
        ]);
    }

    /**
     * Realize the request
     *
     * @return mixed
     */
    abstract public function send();

    /**
     * @param null|ResponseInterface $response
     *
     * @return ApiResponse
     */
    abstract public function handleResponse(?ResponseInterface $response): ApiResponse;

    /**
     * Return final url to send request
     *
     * @return string
     * @throws UndefinedUrlPropertyException
     */
    public function getUrl(): string
    {
        preg_match_all('#{(.*?)}#m', $this->path, $matches);
        foreach ($matches[0] as $index => $param) {
            if (! property_exists($this, $matches[1][$index])) {
                throw new UndefinedUrlPropertyException($matches[1][$index]);
            }
            $url = str_replace($param, $this->{$matches[1][$index]}, $this->path);
        }

        return $url;
    }

    /**
     * Check if is in sandbox mode
     *
     * @return bool
     */
    public function isSandboxMode(): bool
    {
        if ($this->getEnv() == 'production') {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getEnv(): string
    {
        return $this->env;
    }

    /**
     * @param string $env
     *
     * @return ApiRequest
     */
    public function setEnv(string $env): ApiRequest
    {
        $this->env = $env;

        return $this;
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * @param string $baseUrl
     *
     * @return ApiRequest
     */
    public function setBaseUrl(string $baseUrl): ApiRequest
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmailUser(): string
    {
        return $this->emailUser;
    }

    /**
     * @param string $emailUser
     *
     * @return ApiRequest
     */
    public function setEmailUser(string $emailUser): ApiRequest
    {
        $this->emailUser = $emailUser;

        return $this;
    }

    /**
     * Client request headers
     *
     * @return array
     */
    protected function requestHeaders(): array
    {
        return [
            'x-api-token-buzzlead' => $this->getApiToken(),
            'x-api-key-buzzlead'   => $this->getApiKey(),
            //            'Accept'               => 'application/json'
        ];
    }

    /**
     * @return string
     */
    public function getApiToken(): string
    {
        return $this->apiToken;
    }

    /**
     * @param string $apiToken
     *
     * @return $this
     */
    public function setApiToken(string $apiToken): ApiRequest
    {
        $this->apiToken = $apiToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     *
     * @return ApiRequest
     */
    public function setApiKey(string $apiKey): ApiRequest
    {
        $this->apiKey = $apiKey;

        return $this;
    }

}