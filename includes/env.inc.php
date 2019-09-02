<?php
$dbenv = Dotenv\Dotenv::create(APP_CONFIG, "db.env");
$dbenv->load();

# Database
define("DB_HOST", getenv("DB_HOST"));
define("DB_NAME", getenv("DB_NAME"));
define("DB_USER", getenv("DB_USER"));
define("DB_PASS", getenv("DB_PASS"));