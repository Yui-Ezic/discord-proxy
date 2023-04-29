<?php

include __DIR__ . '/vendor/autoload.php';

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Intents;
use Discord\WebSockets\Event;

$discord = new Discord([
    'token' => 'bot-token',
    'intents' => Intents::getAllIntents()
]);

$discord->on('ready', function (Discord $discord) {
    echo "Bot is ready!", PHP_EOL;

    // Listen for messages.
    $discord->on(Event::MESSAGE_CREATE, function (Message $message, Discord $discord) {
        echo "{$message->author->username}: {$message->content}", PHP_EOL;
        $client = new \GuzzleHttp\Client();
        $client->post('https://php-fpm-rvjncn6ntq-lm.a.run.app/discord/webhook', [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'message' => [
                    'from' => $message->author->username,
                    'text' => $message->content
                ]
            ]
        ]);
    });
});

$discord->run();