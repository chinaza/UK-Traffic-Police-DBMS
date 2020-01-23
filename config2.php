<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['isLoggedIn'])) {
    //Initialize loggedIn session variable if not set
    $_SESSION['isLoggedIn'] = "false";
}

class Database
{

    // Database connection parameters
    private $host = "127.0.0.1";
    private $username = "root";
    private $password = "zhaptek";
    private $database = "finalcw";
    public $conn;

    public function __construct()
    {
        // Initiate connection to MYSQL sesrver
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);

        // Throw error if failed to connec to MYSQL sesrver
        if ($this->conn->connect_errno) {
            echo "Failed to connect to MySQL: " . $this->conn->connect_error;
            exit();
        }
    }

    public function query(String $q, Bool $limit = false)
    {
        echo $q . "\n";
        $arrResult = array();
        $result = $this->conn->query($q);

        $res = array();

        if (!$result) {
            return false;
        }

        // Handle query results which do not return rows
        if (gettype($result) == "boolean") {
            if ($this->conn->affected_rows < 1) {
                return 2;
            } else if (!$result) {
                return false;
            } else {
                return true;
            }
        }

        // Return empty array for empty row set
        if ($result->num_rows < 1) {
            return [];
        }

        // Store result in array
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $res[] = $row;
        }

        // Return single row if limit set to true else entire row set
        return $limit ? $res[0] : $res;
    }

    public function close()
    {
        // Close MYSQL Connection
        $this->conn->close();
    }

}

$db = new Database();
