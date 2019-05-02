<?php

class MySQLData
{
    /**
     * @var string
     */
    private $mysqlPath, $user, $password, $host, $dbName, $savePath, $filename, $message;


    function __construct($mysqlPath, $user, $password, $host, $dbName, $savePath = null, $filename = null)
    {
        $this->mysqlPath = $mysqlPath;
        $this->user = $user;
        $this->password = $password;
        $this->host = $host;
        $this->dbName = $dbName;
        $this->savePath = $savePath;
        $this->filename = $filename;
        $this->message = null;
    }

    function Save()
    {
        if ($this->TestParam()) {
            shell_exec($this->mysqlPath . ' --user=' . $this->user . ' --password=' . $this->password . ' --host=' . $this->host . ' ' . $this->dbName . ' > ' . $this->savePath . $this->filename);
            echo 'File ' . $this->filename . ' saved successfully at ' . $this->savePath;
        } else {
            echo $this->message;
        }
    }

    private function TestParam()
    {
        // Testing if File Exists.
        if (!file_exists($this->mysqlPath . '\\bin\\mysqldump.exe')) {
            $this->message .= 'MySQL Path is Incorrect.';
        } else {
            $this->mysqlPath .= '\\bin\\mysqldump.exe';
        }
        // Testing MySQL Connection parameters.
        $conn = new mysqli($this->host, $this->user, $this->password, $this->dbName);
        if ($conn->connect_error) {
            $this->message .= $conn->connect_error;
        } else {
            $conn->close();
        }
        // Testing savePath, if null then create a default.
        if ($this->savePath == null) {
            $this->savePath = '.\\';
        }
        // Testing fileName, if null then create a default.
        if ($this->filename == null) {
            date_default_timezone_set("America/New_York");
            $filename = 'database_' . $this->dbName . '_backup_' . date('g_a_d_m_y') . '.sql';
            $this->filename = $filename;
        }
        // If message == null then there is no errors.
        if ($this->message == null) {
            return true;
        } else return false;
    }
}