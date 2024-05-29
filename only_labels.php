<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
// Чтение файла filtered_labels.csv
$lines = file('filtered_labels.csv', FILE_IGNORE_NEW_LINES);

// Создание нового файла onlylabels.csv и запись данных
$onlyLabelsFile = fopen('onlylabels.csv', 'w');

// Проход по каждой строке файла
foreach ($lines as $line) {
    // Разделение строки на столбцы по символу '$'
    $columns = explode('$', $line);

    // Запись первого столбца в файл onlylabels.csv
    fwrite($onlyLabelsFile, $columns[0] . "\n");
}

fclose($onlyLabelsFile);
?>