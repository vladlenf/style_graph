<?php
//Удаляем повторы с маленьким количестом помет из файла merged_labels.csv
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
// Чтение файла merged_labels.csv
$lines = file('merged_labels.csv', FILE_IGNORE_NEW_LINES);

// Создание нового файла filtered_labels.csv и запись данных
$filteredFile = fopen('filtered_labels.csv', 'w');

// Создание ассоциативного массива для хранения данных
$filteredData = [];

// Проход по каждой строке файла
foreach ($lines as $line) {
    list($firstColumn, $secondColumn) = explode('$', $line);

    // Если значение второго столбца уже существует в массиве
    if (array_key_exists($secondColumn, $filteredData)) {
        // Сравниваем длину первого столбца и заменяем, если новая строка имеет более длинный первый столбец
        if (strlen($firstColumn) > strlen($filteredData[$secondColumn])) {
            $filteredData[$secondColumn] = $firstColumn;
        }
    } else {
        $filteredData[$secondColumn] = $firstColumn;
    }
}

// Записываем отфильтрованные данные в новый файл
foreach ($filteredData as $secondColumn => $firstColumn) {
    fwrite($filteredFile, $firstColumn . '$' . $secondColumn . "\n");
}

fclose($filteredFile);
?>