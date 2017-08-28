<?php

require_once __DIR__.'/vendor/autoload.php';

$loader = new Twig_Loader_Array([
    'index' => '{% gimme article with {\'foo\': \'bar\'} %} {{ dump(article) }} {% endgimme %}',
]);
$twig = new Twig_Environment($loader, ['debug' => true]);
$twig->addExtension(new \Twig_Extension_Debug());
$twig->addExtension(new \SWP\Extension\GimmeExtension());

echo $twig->render('index');
