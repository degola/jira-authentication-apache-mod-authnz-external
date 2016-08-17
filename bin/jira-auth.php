#!/usr/bin/php
<?php

require __DIR__ . "/../vendor/autoload.php";

if(sizeof($argv) <= 3) {
  exit(1);
}

$jiraBase = trim($argv[1]);
$logstash = trim($argv[3]);
$group = trim($argv[1]);
if(!isset($_SERVER["HTTP_HOST"])) $_SERVER["HTTP_HOST"] = "unknown";
if(!isset($_SERVER["URI"])) $_SERVER["URI"] = "unknown";
if(!isset($_SERVER["IP"])) $_SERVER["IP"] = "unknown";


$authenticator = new \Groar\JiraAuthentication\Authenticator($jiraBase);
$logger = new \Monolog\Logger("JiraAuthenticationProvider");
$handler = new \Monolog\Handler\SocketHandler($logstash, "WARNING");
$formatter = new \Groar\JiraAuthentication\LoggerLogStashFormatter("JiraAuthenticationProvider");
$handler->setFormatter($formatter);
$logger->pushHandler($handler);

$fp = fopen("php://stdin", "r");
$username = trim(fgets($fp, 8192));
$password = trim(fgets($fp, 8192));
fclose($fp);

if($authenticator->auth($username, $password, $group)) {
  $logger->addInfo($username." logged into ".$_SERVER["HTTP_HOST"]." with ip ".$_SERVER["IP"], array("HTTP_HOST" => $_SERVER["HTTP_HOST"], "URI" => $_SERVER["URI"]));
  exit(0);
}
$logger->addWarning($username." failed to log into ".$_SERVER["HTTP_HOST"]." with ip ".$_SERVER["IP"]);
exit(1);

?>