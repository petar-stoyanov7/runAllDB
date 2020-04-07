<?php
class DB
{
    private $databases;
    private $testDatabases;
    private $config;

    public function __construct()
    {
        $dbSettings = file_get_contents('./conf/databases.json');
        $dbSettings = json_decode($dbSettings, true);

        $this->databases = $dbSettings['databases'];
        $this->testDatabases = $dbSettings['testDatabases'];
        $this->config = $dbSettings['settings'];
    }

    private function getConnection($db)
    {
        $host = $this->config['host'];
        $usr = $this->config['user'];
        $pwd = $this->config['pass'];
        $opt = $this->config['opt'];
        return new PDO("mysql:host=$host;dbname=$db", $usr, $pwd, $opt);
    }

    public function execute($db, $sql)
    {
        $connection = $this->getConnection($db);
        $connection->exec($sql);
    }

    public function getData($db, $sql)
    {
        $connection = $this->getConnection($db);
        $result = $connection->query($sql);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDatabases($test = false)
    {
        if (!$test) {
            return $this->databases;
        }
        return array_merge($this->databases, $this->testDatabases);
    }



}
?>