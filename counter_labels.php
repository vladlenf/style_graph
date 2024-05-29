<?php
//Подчет попрно встречающихся словарных помет внутри одного слова
$file = fopen("onlylabels.csv", "r");

$wordsCount = [];
$wordsPairsCount = [];

while (!feof($file)) {
    $line = fgets($file);
    
    // Проверяем, что $line является строкой
    if (is_string($line)) {
        $words = explode(" ", $line);
        
        // Удаляем пробелы из каждого элемента массива
        $words = array_map('trim', $words);

        foreach ($words as $word) {
            // Проверяем длину слова и его содержание
            if (strlen($word) >= 4 && !preg_match('/[{},\[\]]/', $word)) {
                if (!isset($wordsCount[$word])) {
                    $wordsCount[$word] = 0;
                }
                $wordsCount[$word]++;
            }
        }

        // Подсчет количества пар слов
        for ($i = 0; $i < count($words); $i++) {
            for ($j = $i + 1; $j < count($words); $j++) {
                $pair = $words[$i] . ':' . $words[$j];
                // Проверяем длину слов и их содержание
                if (strlen($words[$i]) >= 4 && strlen($words[$j]) >= 4 && !preg_match('/[{},\[\]]/', $words[$i]) && !preg_match('/[{},\[\]]/', $words[$j])) {
                    if (!isset($wordsPairsCount[$pair])) {
                        $wordsPairsCount[$pair] = 0;
                    }
                    $wordsPairsCount[$pair]++;
                }
            }
        }
    }
}

fclose($file);

arsort($wordsPairsCount); // Сортировка массива по убыванию значений

$outputFile = fopen("labels_edges.csv", "w");

foreach ($wordsPairsCount as $pair => $count) {
    print_r($pair . "\n");
    fwrite($outputFile, "{$pair}:{$count}\n");
}

fclose($outputFile);
