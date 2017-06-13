<?php

require 'vendor/autoload.php';

use Mpociot\BotMan\BotManFactory;
use React\EventLoop\Factory;
use Mpociot\BotMan\Messages\Message;

$loop = Factory::create();

$botman = BotManFactory::createForRTM([
    'slack_token' => 'xoxb-196287387540-AHOhFA8lPkKSHqg1cbR0LthU'
], $loop);

$botman->hears('hello', function(\Mpociot\BotMan\BotMan $bot) {
    $bot->typesAndWaits(2);
    $bot->reply(':wave:');
});

$botman->hears('I am {name}', function(\Mpociot\BotMan\BotMan $bot, $name){
    $bot->typesAndWaits(2);
    $bot->userStorage()->save([
         'name' => $name
    ]);
    $bot->typesAndWaits(2);
    $bot->reply('Your name is ' . $name);
});

$botman->hears('I want ([0-9]+)', function (\Mpociot\BotMan\BotMan $bot, $number) {
    $number = $number + 1;
    $bot->typesAndWaits(2);
    $bot->reply('You will get : '. $number);
});

$botman->hears('Hi', function($bot) {
    $user = $bot->getUser();
    $bot->typesAndWaits(2);
    $bot->reply('Hello '.$user->getFirstName().' '.$user->getLastName());
    $bot->typesAndWaits(1);
    $bot->reply('your username is: '.$user->getUsername());
});

$botman->hears('Forget me', function($bot){
    $bot->typesAndWaits(2);
    $bot->userStorage()->delete();
    $bot->reply('I don\'t know anymore who you are.');
});

$botman->hears('Who am I', function($bot){
    $user = $bot->userStorage()->get();
    if ($user->has('name')){
        $bot->typesAndWaits(2);
        $bot->reply('Your are ' . $user->get('name'));
    }
    else{
        $bot->typesAndWaits(2);
        $bot->reply('I don\'t know you yet.');
    }
});

$botman->hears('{emoji}',function($bot, $emoji){
    if ($emoji[0] == ":" and $emoji[strlen($emoji)-1] == ":"){
        $bot->typesAndWaits(2);
        $bot->reply('Nice emoji ! :+1:');
    }
});

$botman->receivesLocation(function ($bot, Location $locate){
    $lat = $locate->getLatitude();
    $lon = $locate->getLongitude();
});

$loop->run();