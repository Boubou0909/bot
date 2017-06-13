<?php

require 'vendor/autoload.php';

use Mpociot\BotMan\BotManFactory;
use React\EventLoop\Factory;
use Mpociot\BotMan\Messages\Message;

$loop = Factory::create();

$botman = BotManFactory::createForRTM([
   'slack_token' => 'xoxb-197653180870-wQ0e9fB26btgngTTQN4K3P6q'
], $loop);

$botman->hears('Warhammer',function($bot){
   $bot->reply('Début du quiz :');
});

$loop->run();