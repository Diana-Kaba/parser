<?php

header("Content-Type: text/html; charset=utf-8");

require "./simplehtmldom-20231112T185928Z-001/simplehtmldom/simple_html_dom.php";

$foxtrot = file_get_html("https://www.foxtrot.com.ua/ru/shop/kharkov/mobilnye_telefony_xiaomi.html");

$links = [];
$names = [];
$prices = [];

$card_titles = count($foxtrot->find('.card__body .card__title'));

if (count($foxtrot->find('.card__body .card__title'))) {
    foreach ($foxtrot->find('.card__body .card__title') as $a) {
        $links[] = $a->href;
        $names[] = $a->innertext;
    }
}

if (count($foxtrot->find('.card-price'))) {
    foreach ($foxtrot->find('.card-price') as $a) {
        $prices[] = $a->innertext;
    }
}

$data = [];
for ($i = 0; $i < count($names); $i++) {
    $data[] = [
        'Назва' => $names[$i],
        'Посилання' => "https://www.foxtrot.com.ua{$links[$i]}",
        'Ціна' => $prices[$i],
    ];
}

$fp = fopen('phones.csv', 'w');

foreach ($data as $row) {
    fputcsv($fp, $row);
}

fclose($fp);
