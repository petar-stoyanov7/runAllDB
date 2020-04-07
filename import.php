<?php
$title = 'IMPORT FILE';
require_once('./db/DB.php');
require_once('header.php');
?>
<div class="container">
<?php

if (
    !empty($_POST) &&
    isset($_POST['table-name']) &&
    isset($_POST['database'])
) {
    $DB = new DB();
    $database = $_POST['database'];
    $tableName = $_POST['table-name'];
}
if (null === $database || null === $tableName) {
    echo "Incorrect data";
    $file = false;
} else {
    $file = fopen($_FILES['import-file']['tmp_name'], 'r');
    $counter = 0;
    $indexes = [];
    $tables = $DB->getData($database, 'SHOW TABLES');
    $tables = array_column($tables,'Tables_in_'.$database);
}
if (in_array($tableName, $tables, true)) {
    echo "<h3>Table `{$tableName}` already exists!</h3>";
    $file = null;
}
if ($file) {
    while ($column = fgetcsv($file, 10000, ',')) {
        if ($counter === 0) {
            $indexes = $column;
            $query = "CREATE TABLE {$tableName} (`idx` INT NOT NULL AUTO_INCREMENT,";
            foreach ($indexes as $row) {
                $query .= "`{$row}` varchar(255) NULL,";
            }
            $query .= 'PRIMARY KEY (`idx`)';
            $query .= ') ENGINE = InnoDB DEFAULT CHARACTER SET = utf8';
            $DB->execute($database, $query);
        } else {
            $query = "INSERT INTO {$tableName} (";
            foreach ($indexes as $row) {
                $query .= "`{$row}`,";
            }
            $query = rtrim($query, ',');
            $query .= ') VALUES ( ';
            foreach ($column as $data) {
                $query .= "'{$data}',";
            }
            $query = rtrim($query, ',');
            $query .= ')';
            $DB->execute($database, $query);
        }
        $counter++;
    }
}
?>
    <h3>Data imported to table <?= $tableName ?></h3>
    <button onclick="location.href='index.php'">back</button>
</div>
