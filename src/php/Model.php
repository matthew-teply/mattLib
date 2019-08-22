<?php

class Model {

    static protected $dbhost = DB_HOST;
    static protected $dbname = DB_NAME;
    static protected $dbuser = DB_USER;
    static protected $dbpass = DB_PASS;

    public function __construct() {
        $this->load    = $this;
        $this->cookies = new Cookies;
        $this->url     = new URL;
        $this->logs    = new Logs;
    }

    public static function connect() {
        $pdo = new PDO("mysql:host=".self::$dbhost.";dbname=".self::$dbname.";charset=utf8mb4", self::$dbuser, self::$dbpass);
        return $pdo;
    }

    public static function query(string $query, array $params = [], bool $escape = true) {
        $stmnt = self::connect()->prepare($query);

        if($escape) {
            foreach($params as $key => $param) {
                $params[$key] = htmlspecialchars($param);
            }
        }

        $stmnt->execute($params);

        if(explode(" ", $query)[0] == "SELECT") {
            $data = $stmnt->fetchAll(PDO::FETCH_ASSOC);

            if($data == null)
                return [$data]; # Return data in array, only containing null in the position 0

            return $data;
        }
    }

    public static function return_json(array $data) {
        echo json_encode($data);
    }

    public static function model($path) {
        require_once MODELS . "/$path.model.php";

        $className = "Model" . implode("", explode("/", $path));

        return new $className;
    }

    public static function tableExists(string $table) {
        if(!isset(self::query("SELECT COUNT(*) FROM $table LIMIT 1")[0]['COUNT(*)']))
            return false;

        return true;
    }
}