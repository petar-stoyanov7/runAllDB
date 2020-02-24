<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./css/main.css">
    <title>Database manipulation</title>
</head>
<body>
<header class="main-header">
    <h1>Run on databases</h1>
</header>


<?php
require_once('./db/DB.php');
$DB = new DB();

function runAllDb($databases, $queries)
{
//if we use SQL file
    $sql = file_get_contents('./conf/queries.sql');
    foreach ($databases as $db) {
        if (is_array($queries)) {
            foreach ($queries as $query) {
                try {
                    echo '<span class="query-title">DB: ['.$db.'] Query:</span>';
                    echo '<pre>' . $query . '</pre>';
                    $DB->execute($db, $query);
                    echo '<span class="success">^Success</span>';
                } catch (Exception $e) {
                    echo "<span>" . $db . " failed</span>";
                    echo '<pre>';
                    echo $e->getMessage();
                    echo '</pre>';
                    echo '<span class="failure">^Fail</span>';
                    echo "<br>";
                }
            }
        } else {
            try {
                echo '<span class="query-title">DB: [' . $db . '] Query:</span>';
                echo '<pre>' . $queries . '</pre>';
                $DB->execute($db, $queries);
                echo '<span class="success">^Success</span>';
            } catch (Exception $e) {
                echo "<span>{$db} failed</span>";
                echo '<pre>';
                echo $e->getMessage();
                echo '</pre>';
                echo '<span class="failure">^Fail</span>';
                echo "<br>";
            }
        }
    }
}
?>
<div class="container">
    <div class="content">
        <form method="post" action="#">
        <div class="flex-container">
            <div class="flex-item">
                <h4>Query</h4>
                <textarea name="query"><?=$sql?></textarea>
            </div>
            <div class="flex-item">
                <h4>Databases:</h4>
                <?php foreach ($DB->getDatabases() as $database) : ?>
                <label>
                    <input type="checkbox" name="db[]" value="<?= $database ?>" checked>
                    <?= $database ?>
                </label>
                <?php endforeach; ?>
            </div>
            <div class="flex-item">
                <button class="run" type="submit">Run on DB</button>
            </div>
        </form>
        </div>
    </div>
    <?php if (isset($_POST['db']) && isset($_POST['query'])) : ?>
    <div class="content">
        <?php runAllDb($_POST['db'], $_POST['query']); ?>
        <a class="run" href="index.php">Clear</a>
    </div>
    <?php endif; ?>
</div>



</body>
</html>