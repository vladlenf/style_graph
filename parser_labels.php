<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
// Чтение исходного файла labels.csv
$lines = file('labels.csv', FILE_IGNORE_NEW_LINES);

// Создание нового файла merged_labels.csv
$mergedFile = fopen('merged_labels.csv', 'w');

// Проход по каждой строке исходного файла
foreach ($lines as $line) {
    list($firstColumn, $secondColumn) = explode('$', $line);
    
    // Поиск других строк с тем же значением второго столбца
    $sameSecondColumn = array_filter($lines, function($l) use ($secondColumn, $line) {
        list($first, $second) = explode('$', $l);
        return $second == $secondColumn && $l != $line;
    });

    // Если найдены строки с одинаковым значением второго столбца, то склеиваем первые столбцы
    if (count($sameSecondColumn) > 0) {
        $mergedLine = $firstColumn;
        foreach ($sameSecondColumn as $sameLine) {
            list($sameFirstColumn, $sameSecondColumn) = explode('$', $sameLine);
            // Исключаем перестановки в первом столбце
            if (strcmp($sameFirstColumn, $firstColumn) > 0) {
                $mergedLine .= ' ' . $sameFirstColumn;
            } else {
                $mergedLine = $sameFirstColumn . ' ' . $mergedLine;
                $firstColumn = $sameFirstColumn;
            }
            // Удаляем обработанные строки из массива
            $lines = array_diff($lines, [$sameLine]);
        }
        
        // Проверяем, существует ли уже склеенная строка в результирующем файле
        if (!in_array($mergedLine . '$' . $secondColumn, file('merged_labels.csv', FILE_IGNORE_NEW_LINES))) {
            fwrite($mergedFile, $mergedLine . '$' . $secondColumn . "\n");
        }
    }
}

fclose($mergedFile);

// Запись обновленных данных обратно в файл labels.csv
file_put_contents('labels.csv', implode("\n", $lines));
?>
