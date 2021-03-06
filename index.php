<?php

require_once("City.php");
require_once("Error.php");

require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

$app->get('/', function () {
    $city = new City();
    echo json_encode($city);
});

$app->get('/lines', function () {
    $city = new City();
    $lineNameList = array();
    foreach($city->lineList as $i) {
        $lineNameList[] = $i->name;
    }
    echo json_encode($lineNameList);
});

$app->get('/line/:name', function($name){
    $city = new City();
    foreach($city->lineList as $i) {
        if($i->name==$name) {
            echo json_encode($i);
            return;
        }
    }
});

$app->get('/line/:name/buses', function($name){
    $city = new City();
    $matchedLinesBusIds = array();
    foreach($city->lineList as $i) {
        if($i->name==$name) {
            foreach($i->busList as $j) {
                $matchedLinesBusIds[] = $j->id;
            }
        }
    }
    echo json_encode($matchedLinesBusIds);
});

$app->get('/line/:name/bus/:id', function($name,$id){
    $city = new City();
    foreach($city->lineList as $i) {
        if($i->name==$name) {
            foreach($i->busList as $j) {
                if($j->id==$id) {
                    echo json_encode($j);
		    return;
                }
            }
        }
    }
});

$app->get('/route/:name', function($name){
    $utils = new Utils();
    $coordinateList;
    $coordinates = explode(" ", $utils->getRoute($name));
    foreach($coordinates as $coordinate) {
      $tempSwap = explode(",", $coordinate);
      $tempCoordinate = new Coordinate();
      $coordinateList[] = $tempCoordinate->withCoor($tempSwap[1],$tempSwap[0]);
    }
    echo json_encode($coordinateList);
});

$app->notFound(function () {
    $error = new Error();
    $error->message="Not Found";
    $error->documentation_url="https://github.com/8cookin/sapi-php";
    echo json_encode($error);
});

$app->run();
