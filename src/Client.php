<?php

namespace Lixus\ValueFirst;

use Exception;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

class Client
{

    /* @var ConfigRepository $config */
    protected $config;

    /* @var Credential $credential */
    private $credential;

    /**
     * Client constructor.
     * @param ConfigRepository $config
     */
    public function __construct(ConfigRepository $config)
    {
        $this->config = $config;

        $this->credential = new Credential(
            $config->get("valuefirst.username") ??"",
            $config->get("valuefirst.password") ?? "",
            $config->get("valuefirst.sender_phone") ?? ""
        );
    }

    /**
     * @param string $to
     * @param string $template
     * @param string|null $tag
     * @param string|null $dlUrl
     * @return Response
     * @throws Exception
     */
    public function send(string $to, string $template, string $tag = null, string $dlUrl = null): Response
    {
        return $this->request($this->buildRequestParams($to, $template, $tag, $dlUrl));
    }

    /**
     * @param string $to
     * @param string $template
     * @param string $tag
     * @param string $dlUrl
     * @return array
     */
    private function buildRequestParams(string $to, string $template, string $tag = null, string $dlUrl = null): array
    {
        return [
            "@VER" => "1.2",
            "USER" => [
                "@USERNAME"      => $this->credential->getUsername(),
                "@PASSWORD"      => $this->credential->getPassword(),
                "@UNIXTIMESTAMP" => "",
            ],
            "DLR"  => [
                "@URL" => $dlUrl,
            ],
            "SMS"  => [
                [
                    "@UDH"          => "0",
                    "@CODING"       => "1",
                    "@TEXT"         => "",
                    "@PROPERTY"     => "0",
                    "@ID"           => "99",
                    "@TEMPLATEINFO" => $template,
                    "ADDRESS"       => [
                        [
                            "@FROM" => $this->credential->getSenderPhone(),
                            "@TO"   => $to,
                            "@SEQ"  => "1",
                            "@TAG"  => $tag,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param array $buildRequestParams
     * @return Response
     * @throws Exception
     */
    private function request(array $buildRequestParams): Response
    {
        $ch = curl_init($this->config->get("valuefirst.base_url"));
        curl_setopt($ch, CURL_HTTP_VERSION_1_1, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($buildRequestParams));
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->config->get("valuefirst.timeout", 10));

        if ($this->config->get("valuefirst.secure_connection", true) === false) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        $response = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $errNo = curl_errno($ch);
        $error = curl_error($ch);

        curl_close($ch);

        if (!$errNo) {
            return new Response($httpStatus, json_decode($response, true) ?? []);
        } else {
            throw new Exception($error);
        }
    }

    /**
     * @param string $username
     * @param string $password
     * @param string $senderPhone
     * @return $this
     */
    public function withCredential(string $username, string $password, string $senderPhone): Client
    {
        $this->credential = new Credential($username, $password, $senderPhone);
        return $this;
    }
}