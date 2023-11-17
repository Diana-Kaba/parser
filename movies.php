<?php

header("Content-Type: text/html; charset=utf-8");

require "./simplehtmldom-20231112T185928Z-001/simplehtmldom/simple_html_dom.php";

$movies = file_get_html("https://multiplex.ua/");

$sessions = [];
$names = [];
$prices = [];
$links = [];

if (count($movies->find('.mpp_title span'))) {
    foreach ($movies->find('.mpp_title span') as $a) {
        $names[] = $a->innertext;
    }
}

if (count($movies->find('.mpp_title'))) {
    foreach ($movies->find('.mpp_title') as $a) {
        $links[] = $a->href;
    }
}

if (count($movies->find('.time span'))) {
    foreach ($movies->find('.time span') as $a) {
        $sessions[] = $a;
    }
}

if (count($movies->find('.price_min_max'))) {
    foreach ($movies->find('.price_min_max') as $a) {
        $prices[] = $a;
    }
}

$ul = [];
$ul[] = "<ul>";
for ($i = 0; $i < count($names); $i++) {
    $ul[] = "<li>
    <a href='https://multiplex.ua/{$links[$i]}'><b>{$names[$i]}</b></a> Сеанс: <i>{$sessions[$i]}</i>. Коштує <i>{$prices[$i]}</i>.
    </li>";
}
$ul[] = "</ul>";

$content = implode("\n", $ul);

$path = "multiplex";
if (!is_dir($path)) {
    mkdir($path);
}

$str_b = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movies</title>
</head>
<body>';
$str_e = '</body>
</html>';

$h = fopen($path."/multiplex.html", "w");

fwrite($h, $str_b."\n");
fwrite($h, $content."\n");
fwrite($h, $str_e);
fclose($h);