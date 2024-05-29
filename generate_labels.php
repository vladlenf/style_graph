<?php
//Скрипт для сохранения словарной пометы и значения в словарной статьи
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
 
$host = 'localhost';
$db_user = 'vladlen';
$db_password = '';
$dbname = "wikt";

 
$mysqli = new mysqli($host, $db_user, $db_password, $dbname);
if ($mysqli->connect_errno) 
{
    printf("Connection error: %s\n", $mysqli->connect_error);
    exit();
}
else
{
    $query = "SET NAMES latin1;";
    $result = $mysqli->query($query);
    $query = "SELECT DISTINCT label.short_name, wiki_text.text from page,lang_pos,wiki_text,label, label_meaning, meaning where page.id=lang_pos.page_id and lang_pos.lang_id=809 and lang_pos.id=meaning.lang_pos_id and meaning.wiki_text_id=wiki_text.id and label_meaning.label_id=label.id and label_meaning.meaning_id=meaning.id and wiki_text.id=meaning.wiki_text_id and label.category_id=17 order by page.id;";
    $result = $mysqli->query($query);
    if ($result->num_rows > 0) {
        // Открываем файл для записи
        $fp = fopen('labels.csv', 'w');
    
        // Записываем результат запроса в файл
        while ($row = $result->fetch_assoc()) {
            fputcsv($fp, $row, $separator = "$");
        }
    
        // Закрываем файл
        fclose($fp);
    
        echo "Результат запроса был сохранен в файл labels.csv";
    } else {
        echo "0 результатов";
    }    

}
$mysqli->close();

