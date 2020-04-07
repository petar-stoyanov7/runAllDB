<?php
$title = 'Run on databases';
require_once('header.php');
require_once('./db/DB.php');
$DB = new DB();
$sql = file_get_contents('./conf/queries.sql');

function runAllDb($databases, $queries)
{
    $DB = new DB();
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

<div class="container">
<div class="content">
    <form enctype="multipart/form-data" method="post" action="import.php">
        <div class="flex-container">
            <div class="flex-item">
                <label for="table-name">Table name:</label>
                <input type="text" name="table-name" id="table-name">
                <input type="file" name="import-file" id="import-file">
            </div>
            <div class="flex-item">
                <h4>Database</h4>
                <select name="database">
                    <?php foreach ($DB->getDatabases(true) as $database) : ?>
                        <option value="<?= $database?>"><?=$database?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="flex-item">
                <button class="run" type="submit" name="submit">Import to DB</button>
            </div>
        </div>
    </form>
</div>
</div>



</body>
</html>