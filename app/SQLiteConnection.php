<?php

namespace App;

/**
 * SQLite connnection
 */
class SQLiteConnection
{
    /**
     * PDO instance
     * @var type 
     */
    private $pdo;

    /**
     * return in instance of the PDO object that connects to the SQLite database
     * @return \PDO
     */
    public function connect()
    {
        if ($this->pdo == null) {
            $this->pdo = new \PDO("sqlite:" . Config::PATH_TO_SQLITE_FILE);
        }
        return $this->pdo;
    }
}

class SQLiteCreateTable
{

    /**
     * PDO object
     * @var \PDO
     */
    private $pdo;

    /**
     * connect to the SQLite database
     */
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * create tables 
     */
    public function createTables()
    {
        $commands = ['CREATE TABLE IF NOT EXISTS program (
                        program_id   INTEGER PRIMARY KEY,
                        channel_name TEXT NOT NULL,
                        program_start_date TEXT,
                        program_title TEXT NOT NULL,
                        program_description TEXT,
                        program_age_restriction TEXT
                      )'];

        // execute the sql commands to create new tables
        foreach ($commands as $command) {
            $this->pdo->exec($command);
        }
    }

    /**
     * Insert a new task into the tasks table
     */
    public function insertProgram($programs)
    {
        $sth = $this->pdo->prepare("DELETE FROM program");
        $sth->execute();

        foreach ($programs as $program) {

            $sql = "INSERT INTO program ( " . implode(', ', array_keys($program)) . ") VALUES (" . implode(', ', array_values($program)) . ");";
            $sth = $this->pdo->prepare($sql);
            $sth->execute();
        }
    }

    /**
     * get the table list in the database
     */
    public function getTableList($channel = null)
    {

        if ($channel != null) {
            $stmt = $this->pdo->prepare('SELECT *       
            FROM program
            WHERE channel_name = :channel;');

            $stmt->execute([':channel' => $channel]);
        } else {
            $stmt = $this->pdo->prepare('SELECT *       
            FROM program
            ;');

            $stmt->execute();
        }

        $result = "<tbody>";
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {

            $result .= "<tr><td>" . $row['channel_name'] . "</td><td>" . $row['program_start_date'] . "</td><td>" . $row['program_title'] . "</td><td>" . $row['program_description'] . "</td></tr>";
        }
        $result .= "</tbody>";

        return $result;
    }

    public function getChannels()
    {

        $stmt = $this->pdo->query("SELECT channel_name
            FROM program
            ");

        $channels = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {

            $channels[] = $row;
        }
        return $channels;
    }
}
