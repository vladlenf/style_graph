<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
// Чтение файла labels_edges.csv
$lines = file('labels_edges.csv', FILE_IGNORE_NEW_LINES);

// Создание ассоциативного массива для хранения сумм чисел
$sums = [];

// Проход по каждой строке файла
foreach ($lines as $line) {
    // Разделение строки на столбцы по символу ':'
    $parts = explode(':', $line);

    // Формирование ключа для ассоциативного массива
    $key1 = $parts[0] . ':' . $parts[1];
    $key2 = $parts[1] . ':' . $parts[0];

    // Проверка, существует ли уже ключ в массиве
    if (array_key_exists($key1, $sums)) {
        // Если ключ существует, то прибавляем число к существующему значению
        $sums[$key1] += (int)$parts[2];
    } elseif (array_key_exists($key2, $sums)) {
        // Если ключ в обратном порядке существует, то прибавляем число к существующему значению
        $sums[$key2] += (int)$parts[2];
    } else {
        // Если ключ не существует, то добавляем его в массив
        $sums[$key1] = (int)$parts[2];
    }
}

// Создание нового файла combined_labels_edges.csv и запись данных
$combinedLabelsFile = fopen('style_labels_edges.csv', 'w');

// Проход по каждому элементу ассоциативного массива и запись в файл
foreach ($sums as $key => $value) {
    fwrite($combinedLabelsFile, $key . ':' . $value . "\n");
}

fclose($combinedLabelsFile);

echo "Файл combined_labels_edges.csv успешно создан.";
?>