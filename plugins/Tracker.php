<?php

class Tracker {

    private $url;
    private $cookies;
    private $model;

    public function __construct() {
        $this->url     = new URL;
        $this->cookies = new Cookies;
        $this->model   = new Model;
    }

    public function init() {
        if(!$this->model->tableExists("tracker_visitors")) { # If visitors table doesn't exist, create it
            $this->model->query("CREATE TABLE tracker_visitors (
                id int(11) not null PRIMARY KEY AUTO_INCREMENT,
                ip varchar(45) not null,
                cookie text not null,
                os text not null,
                browser text not null,
                date_created timestamp not null DEFAULT CURRENT_TIMESTAMP,
                alias text not null
            ) CHAR SET 'utf8'");
        }

        if(!$this->model->tableExists("tracker_visits")) { # If visitors table doesn't exist, create it
            $this->model->query("CREATE TABLE tracker_visits (
                id int(11) not null PRIMARY KEY AUTO_INCREMENT,
                page text not null,
                visitor_id int(11) not null,
                date_created timestamp not null DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (visitor_id) REFERENCES tracker_visitors(id)
            ) CHAR SET 'utf8'");
        }
    }

    public function getVisitorCookie() {
        return $this->cookies->get("visited");
    }

    public function getVisitorId(string $cookie = null) {
        if($cookie == null)
            $cookie = $this->getVisitorCookie();

        return $this->model->query("SELECT id FROM tracker_visitors WHERE cookie=:cookie", [":cookie" => $cookie])[0]['id'];
    }

    public function setVisitor() {
        list($usec, $sec) = explode(" ", microtime());

        $cookie_val = "".md5("".$sec.".".$usec."")."";
        $remaining_hours = 24 - date('G');

        $this->cookies->set(
            "visited",
            $cookie_val,
            (3600*$remaining_hours) - ((int)date('i')*60 + (int)date('s'))
        );

        $ip      = $this->url->getClientIp();
        $cookie  = $cookie_val;
        $os      = $this->url->getClientOs();
        $browser = $this->url->getClientBrowser();

        @$visitor = $this->model->query("SELECT * FROM tracker_visitors WHERE ip=:ip AND os=:os AND browser=:browser", [
            ":ip" => $ip,
            ":os" => $os,
            ":browser" => $browser
        ])[0];

        if(!empty($visitor)) {
            $date_created = date("Y-m-s H:i:s");

            $this->model->query("UPDATE tracker_visitors SET cookie=:cookie, date_created=:date_created WHERE ip=:ip", [
                ":cookie"       => $cookie,
                ":ip"           => $ip,
                ":date_created" => $date_created
            ]);
        }

        else {
            $alias = "";

            @$alias = $this->model->query("SELECT alias FROM tracker_visitors WHERE ip=:ip", [
                ":ip" => $ip
            ])[0]['alias'];

            $this->model->query("INSERT INTO tracker_visitors (ip, cookie, os, browser, alias) VALUES (:ip, :cookie, :os, :browser, :alias)", [
                ":ip"      => $ip,
                ":cookie"  => $cookie,
                ":os"      => $os,
                ":browser" => $browser,
                ":alias"   => $alias
            ]);
        }

        return $cookie_val;
    }

    public function setPageVisit(string $page) {
        if(@$this->cookies->get("visited") == null)
            $id = $this->getVisitorId($this->setVisitor());
        else {
            @$id = $this->getVisitorId();

            if($id === null) {
                $this->cookies->destroy("visited");
                $id = $this->getVisitorId($this->setVisitor());
            }
        }

        if($this->model->query("SELECT COUNT(*), DATE_FORMAT(tracker_visits.date_created, '%Y-%m-%d') FROM tracker_visits WHERE visitor_id=:visitor_id AND page=:page AND DATE(date_created) = CURDATE()", [
            ":visitor_id" => $id,
            ":page"       => $page
        ])[0]['COUNT(*)'] == 0)
            $this->model->query("INSERT INTO tracker_visits (page, visitor_id) VALUES (:page, :visitor_id)", [
                ":page"       => $page,
                ":visitor_id" => $id
            ]);
    }

    public function getPageVisits() {
        $pages = $this->model->query("SELECT 
        `page`,
        COUNT(`page`) AS `visits` 
        FROM     `tracker_visits`
        GROUP BY `page`
        ORDER BY `visits` DESC;");

        return $pages;
    }

    public function getOs() {
        $os = $this->model->query("SELECT 
        `os`,
        COUNT(`os`) AS `occurences` 
        FROM     `tracker_visitors`
        GROUP BY `os`
        ORDER BY `occurences` DESC;");

        return $os;
    }

    public function getBrowsers() {
        $browser = $this->model->query("SELECT 
        `browser`,
        COUNT(`browser`) AS `occurences` 
        FROM     `tracker_visitors`
        GROUP BY `browser`
        ORDER BY `occurences` DESC;");

        return $browser;
    }

    public function getVisits() {
        return $this->model->query("SELECT * FROM tracker_visits");
    }

    public function getAllVisits() {
        return $this->model->query("SELECT COUNT(*) FROM tracker_visits")[0]['COUNT(*)'];
    }

    public function getAllVisitorsCount() {
        return $this->model->query("SELECT COUNT(DISTINCT ip) FROM tracker_visitors")[0]['COUNT(DISTINCT ip)'];
    }

    public function getMonthVisits() {
        $month = date("n");
        $year = date("Y");

        return $this->model->query("SELECT * FROM tracker_visits 
        WHERE MONTH(date_created)=$month
        AND YEAR(date_created)=$year");
    }

    public function getDayVisits() {
        return $this->model->query("SELECT * FROM tracker_visits WHERE DATE(date_created) = CURDATE()");
    }

    public function getDayVisitsCount() {
        return $this->model->query("SELECT COUNT(*) FROM tracker_visits WHERE DATE(date_created) = CURDATE()")[0]['COUNT(*)'];
    }

    public function getVisitors(int $page = 0) {
        $ips = $this->model->query("SELECT DISTINCT ip FROM tracker_visitors ORDER BY date_created desc");

        foreach($ips as $key => $ip) {
            $ips[$key] = $this->getVisitorInstances($ip['ip'], $page);
            $ips[$key][0]['visits'] = $this->getInstanceVisits($ips[$key][0]['id']);
        }

        return $ips;
    }

    private function getVisitorInstances(string $ip, int $page = 0) {
        $offset = $page * 10;

        $instances = $this->model->query("SELECT * FROM tracker_visitors WHERE ip=:ip ORDER BY date_created desc LIMIT 10 OFFSET $offset", [
            ":ip" => $ip
        ]);

        foreach($instances as $key => $instance) {
            $instances[$key]['visits'] = $this->getInstanceVisits($instance['id']);
        }

        return $instances;
    }

    private function getInstanceVisits(int $id) {
        return $this->model->query("SELECT * FROM tracker_visits WHERE visitor_id=:id ORDER BY date_created asc", [
            ":id" => $id
        ]);
    }

}