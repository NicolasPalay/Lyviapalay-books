<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
private $apiKey = "7fe35253294278def14fd2a03fc3101e";
private $apikeyPassword = "43c140a9ef2e6544cf8f01bc132aec4e";

public function send($toMail, $toName, $suject,$content){
    $mj = new Client($this->apiKey, $this->apikeyPassword,true,['version' => 'v3.1']);
    $body = [
        'Messages' => [
            [
                'From' => [
                    'Email' => "nicolassam33@gmail.com",
                    'Name' => "Lyvia Palay"
                ],
                'To' => [
                    [
                        'Email' => "$toMail",
                        'Name' => "$toName"
                    ]
                ],
                'TemplateID' => 5039164,
                'TemplateLanguage' => true,
                'Subject' => "$suject",
                'Variables' => [
                    'content' => "$content"
                ]
            ]
        ]
    ];
    $response = $mj->post(Resources::$Email, ['body' => $body]);
    $response->success();
}
}

