<?php

require 'vendor/autoload.php';

use Mpociot\BotMan\BotManFactory;
use React\EventLoop\Factory;
use Mpociot\BotMan\Messages\Message;

$loop = Factory::create();

$etat = 0;
$compteur = 0;
$points = 0;
$ligne =' ';
$number = 0;

$botman = BotManFactory::createForRTM([
   'slack_token' => 'xoxb-197653180870-wQ0e9fB26btgngTTQN4K3P6q'
], $loop);

$botman->hears('Warhammer begin',function($bot) use (&$etat, &$compteur, &$ligne, &$number, &$fichier, &$compteur){
   $bot->reply('Début du quiz :');
   $fichier = fopen('figurines.txt', 'r');
   rewind($fichier);
   $etat = 2;
   $number = rand(0,2);
   for($i = 0; $i <= $number; $i++){
       $ligne = fgets($fichier);
       $ligne = fgets($fichier);
   };
   $ligne = fgets($fichier);
   $compteur = $compteur + 1;
   $bot->reply('Question n°' . $compteur);
   $bot->reply($ligne);
   $bot->reply('Qui est-ce ?');
   $bot->reply('(Pour répondre tapez : "Cette figurine est ...".)');
   $ligne = fgets($fichier);
   fclose($fichier);
});

$botman->hears('Cette figurine est {answer}',function($bot, $answer) use (&$etat, &$points, &$ligne, &$number, &$compteur){
    $answer = trim($answer);
    $ligne = trim($ligne);
    if($ligne === $answer){
        $points = $points + 1;
        $bot->reply('Bonne réponse !');
        $bot->reply('Ton score est de : ' . $points);
    }
    else if($etat == 2 and $answer == 'End'){
        $etat = 0;
        $bot->reply("Ton score est de " . $points . ' sur ' . $compteur);
    }
    else if ($etat != 2){
        $bot->reply("Pour commencer une partie, tapez : \"Warhammer begin\".");
    }
    else if ($etat == 2 and $answer != $ligne){
        $bot->reply("Ce n'est pas la bonne réponse.");
    }
    $bot->reply('Taper "Ok" pour continuer et "End" pour finir.');
});

$botman->hears("{continue}",function($bot, $continue) use(&$etat, &$points, &$ligne, &$number, &$compteur) {
    if ($continue == "Ok") {
        $fichier = fopen('figurines.txt', 'r');
        rewind($fichier);
        $number = rand(0,2);
        for ($i = 0; $i <= $number; $i++) {
            $ligne = fgets($fichier);
            $ligne = fgets($fichier);
        };
        $ligne = fgets($fichier);
        $compteur = $compteur + 1;
        $bot->reply('Question n°' . $compteur);
        $bot->reply($ligne);
        $bot->reply('Qui est-ce ?');
        $bot->reply('(Pour répondre tapez : "Cette figurine est ...".)');
        $ligne = fgets($fichier);
        fclose($fichier);
    }
    else if($continue == "End"){
        $etat = 0;
        $bot->reply("Ton score est de " . $points . ' sur ' . $compteur);
    };
});

$loop->run();