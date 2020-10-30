<?php

require_once '../../vendor/autoload.php';

use VK\CallbackApi\Server\VKCallbackApiServerHandler;
use VK\Actions\Messages;
use VK\Client\VKApiClient;

const TOKEN = "d7d020d6dbef32f2d564c0fb681a8e35c1e9d97a58da4f252678bfb86c4ffc58a3a44aba42116d0ed0c78";

class ServerHandler extends VKCallbackApiServerHandler
{
    const SECRET = 'A3F1F9794A45AEFE7323D4F56F678';
    const GROUP_ID = 197396031;
    const CONFIRMATION_TOKEN = '2db3fd8f';

    private function createButton()
    {
        $keyboard = [
            'one_time' => true,
            'buttons' => [
                [
                    [
                        'action' => [
                            'type' => 'text',
                            'label' => 'Получить афишу',
                            "payload" => "{\"button\": \"1\"}",
                        ],
                        'color' => 'positive',
                    ]
                ]
            ],
        ];
        return json_encode($keyboard, JSON_UNESCAPED_UNICODE);
    }

    function confirmation(int $group_id, ?string $secret)
    {
        if ($secret === static::SECRET && $group_id === static::GROUP_ID) {
            echo static::CONFIRMATION_TOKEN;
        }
    }

    public function messageNew(int $group_id, ?string $secret, array $object)
    {
        $user_id = $object['user_id'];
        $body = $object['body'];
        $vk = new VKApiClient();
        switch ($body) {
            case "Начать":
                $params = [
                    'message' => "Привет. Ты за афишой? Тогда нажми на кнопку ниже!",
                    'user_id' => $user_id,
                    'random_id' => time(),
                    'keyboard' => $this->createButton(),
                ];
                $result = $vk->messages()->send(TOKEN, $params);
                break;
            case "Получить афишу":
//                $docParams = [
//                    'type' => 'doc',
//                    'peer_id' => $user_id,
//                ];
//                $doc = $vk->docs()->getMessagesUploadServer(TOKEN, $docParams);
                $params2 = [
                    'message' => "Держи. Не забудь опубликовать афишу в своих сторис, чтобы получить скидку!",
                    'user_id' => $user_id,
                    'keyboard' => $this->createButton(),
                    'random_id' => time(),
                ];
                $result2 = $vk->messages()->send(TOKEN, $params2);
                    $params1 = [
                        'message' => "https://drive.google.com/file/d/1ing892IF5sMG6yaPVXsOqWssQlffB5yb/view?usp=sharing",
                        'user_id' => $user_id,
                        'keyboard' => $this->createButton(),
                        'random_id' => time()+1,
                    ];
                    $result1 = $vk->messages()->send(TOKEN, $params1);

                break;
            default:
                $params = [
                    'message' => "Я не знаю этой команды",
                    'user_id' => $user_id,
                    'random_id' => time(),
                ];
                $result = $vk->messages()->send(TOKEN, $params);
        }
        echo 'ok';
    }
}

$handler = new ServerHandler();
$data = json_decode(file_get_contents('php://input'));
$handler->parse($data);


