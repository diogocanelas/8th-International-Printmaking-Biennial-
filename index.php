<?php
require "inc/config.php";
require "inc/database.php";
require 'models/artist.php';
require 'models/prints.php';
require 'models/users.php';


require 'vendor/autoload.php';
date_default_timezone_set('Europe/Paris');


$app = new \Slim\Slim();

$view = $app->view();
$view->parserOptions = array(
    'debug' => true
);
$view->parserExtensions = array(
    new \Slim\Views\TwigExtension(),
);
$app->add(new \Slim\Middleware\SessionCookie());


//-------------------------------------------INDEX-----------------------------------------------------


function search($db){
  $app = \Slim\Slim::getInstance();
  $searchBy=$app->request->post('search-by');
  $search=$app->request->post('search-words');
  $edition=$app->request->post("bienal-edition");
  $type=$app->request->post("type");
  $cleanSearchBy = filter_var($searchBy, FILTER_SANITIZE_STRING);
  $cleanEdition = filter_var($edition, FILTER_SANITIZE_STRING);
  $cleanType = filter_var($type, FILTER_SANITIZE_STRING);
  $oArtist = new Artist();
  $oPrint = new Prints();
  if(!empty($search)){
    $cleanSearch = filter_var($search, FILTER_SANITIZE_STRING);
    if($cleanSearchBy=="artist"){
      if($cleanType=="artists"){
        $artists=$oArtist->getArtistsbyName($db,$cleanSearch,$cleanEdition);
      }else{
        $prints=$oPrint->getPrintsbyArtist($db,$cleanSearch,$cleanEdition);
      }
    }else if($cleanSearchBy=="country"){
      if($cleanType=="artists"){
        $artists=$oArtist->getArtistsbyCountry($db,$cleanSearch,$cleanEdition);
      }else{
        $prints=$oPrint->getPrintsbyCountry($db,$cleanSearch,$cleanEdition);
      }
    }else if($cleanSearchBy=="technique"){
      if($cleanType=="prints" || $cleanType=="inventory"){
        $prints=$oPrint->getPrintsbyTechnique($db,$cleanSearch,$cleanEdition);
      }
    }
    if($cleanType=="artists"){
      $app->render('index.php', array(
        'artists' => $artists, 
        'searchBy' => $cleanSearchBy,
        'search' => $cleanSearch, 
        'edition' => $cleanEdition
      ));
    }else if($cleanType=="prints"){
      $app->render('prints.php', array(
        'prints' => $prints, 
        'searchBy' => $cleanSearchBy,
        'search' => $cleanSearch, 
        'edition' => $cleanEdition
      ));
    }else{
      $app->render('inventory.php', array(
        'prints' => $prints, 
        'searchBy' => $cleanSearchBy,
        'search' => $cleanSearch, 
        'edition' => $cleanEdition
      ));
    }  
  }else{
    if($edition=="all"){
      if($cleanType=="artists"){
        $artists=$oArtist->getAllArtists($db,$searchBy);
      }else{
        $prints=$oPrint->getAllPrints($db,$searchBy);
      }
    }else{
      if($cleanType=="artists"){
        $artists=$oArtist->getAllArtistsbyEdition($db,$searchBy,$cleanEdition);
      }else{
        $prints=$oPrint->getAllPrintsbyEdition($db,$searchBy,$cleanEdition);
      }
    }
    if($cleanType=="artists"){
      $app->render('index.php', array(
        'artists' => $artists, 
        'searchBy' => $cleanSearchBy , 
        'edition' => $cleanEdition
      ));
    }else if($cleanType=="prints"){ 
      $app->render('prints.php', array(
        'prints' => $prints, 
        'searchBy' => $cleanSearchBy, 
        'edition' => $cleanEdition
      ));
    }else{
      $app->render('inventory.php', array(
        'prints' => $prints, 
        'searchBy' => $cleanSearchBy, 
        'edition' => $cleanEdition
      ));
    }
  }
}


function addArtist($db){
  $app = \Slim\Slim::getInstance();
  $artistName=$app->request->post('artist-name');
  $artistCountry=$app->request->post('artist-country');
  $artistEmail=$app->request->post("artist-email");
  $artistBio=$app->request->post("artist-bio");
  $cleanArtistName = filter_var($artistName, FILTER_SANITIZE_STRING);
  $cleanArtistCountry = filter_var($artistCountry, FILTER_SANITIZE_STRING);
  $cleanArtistEmail = filter_var($artistEmail, FILTER_SANITIZE_EMAIL);
  $cleanArtistBio = filter_var($artistBio, FILTER_SANITIZE_STRING);
  $oArtist = new Artist();
  $id=$oArtist->addArtist($db,$cleanArtistName, $cleanArtistCountry, $cleanArtistEmail, $cleanArtistBio);
  if (!is_null($id)){
     $app->flash('success', 'O artista <a style="color:gold;" href="artist?id='.$id.'" >'.$cleanArtistName.'</a> foi inserido com sucesso');
  }else{
    $app->flash('fail', 'Algo correu mal! Tente de novo');
  }
  $artists=$oArtist->getAllArtists($db,"");
  $app->redirect('index');
}


function deleteArtist($db){
  $app = \Slim\Slim::getInstance();
  $artistId=$app->request->post('artist-id');
  $cleanArtistId = filter_var($artistId, FILTER_SANITIZE_NUMBER_INT);
  $artistName=$app->request->post('artist-name');
  $cleanArtistName = filter_var($artistName, FILTER_SANITIZE_STRING);
  $oArtist = new Artist();
  $oPrint = new Prints();
  $deleted=$oArtist->deleteArtist($db,$cleanArtistId);
  if ($deleted){
     $oPrint->deletePrintbyArtist($db,$cleanArtistId);
     $app->flash('success', 'O artista '.$cleanArtistName.' foi eliminado com sucesso');
  }else{
    $app->flash('fail', 'Algo correu mal! Tente de novo');
  }
  $app->redirect('index');
}


function addPrint($db){
  $app = \Slim\Slim::getInstance();
  $printName=$app->request->post('print-name');
  $printYear=$app->request->post('print-year');
  $printTechnique=$app->request->post("print-technique");
  $printDimensions=$app->request->post("print-dimensions");
  $printBienal=$app->request->post("bienal-edition");
  $artistId=$app->request->post("artist-id");
  $cleanPrintName = filter_var($printName, FILTER_SANITIZE_STRING);
  $cleanPrintYear = filter_var($printYear, FILTER_SANITIZE_NUMBER_INT);
  $cleanPrintTechnique = filter_var($printTechnique, FILTER_SANITIZE_STRING);
  $cleanPrintDimensions = filter_var($printDimensions, FILTER_SANITIZE_STRING);
  $cleanPrintBienal = filter_var($printBienal, FILTER_SANITIZE_NUMBER_INT);
  $cleanArtistId = filter_var($artistId, FILTER_SANITIZE_NUMBER_INT);
  $oPrint = new Prints();
  $id=$oPrint->addPrint($db,$cleanArtistId,$cleanPrintName, $cleanPrintYear, $cleanPrintTechnique, $cleanPrintDimensions, $cleanPrintBienal);
  if (!is_null($id)){
     $app->flash('success', 'A obra <a style="color:gold;">'.$cleanPrintName.'</a> foi inserida com sucesso');
  }else{
    $app->flash('fail', 'Algo correu mal! Tente de novo');
  }
  $app->redirect('artist?id='.$cleanArtistId);
}

function deletePrint($db){
  $app = \Slim\Slim::getInstance();
  $printId=$app->request->post('print-id');
  $cleanPrintId = filter_var($printId, FILTER_SANITIZE_NUMBER_INT);
  $artistId=$app->request->post('artist-id');
  $cleanArtistId = filter_var($artistId, FILTER_SANITIZE_NUMBER_INT);
  $printName=$app->request->post('print-name');
  $cleanPrintName = filter_var($printName, FILTER_SANITIZE_STRING);
  $oPrint = new Prints();
  $deleted=$oPrint->deletePrint($db,$cleanPrintId);
  if ($deleted){
     $app->flash('success', 'A obra '.$cleanPrintName.' foi eliminada com sucesso');
  }else{
    $app->flash('fail', 'Algo correu mal! Tente de novo');
  }
  $app->redirect('artist?id='.$cleanArtistId);
}

function addInventory($db){
  $app = \Slim\Slim::getInstance();
  $printId=$app->request->post('print-id');
  $cleanPrintId = filter_var($printId, FILTER_SANITIZE_NUMBER_INT);
  $printName=$app->request->post('print-name');
  $cleanPrintName = filter_var($printName, FILTER_SANITIZE_STRING);
  $oPrint = new Prints();
  $updated=$oPrint->addInventory($db,$cleanPrintId);
  if ($updated){
     $app->flash('success', 'A obra '.$cleanPrintName.' foi adicionada ao Inventário com sucesso');
  }else{
    $app->flash('fail', 'Algo correu mal! Tente de novo');
  }
  $app->redirect('prints');
}

function removeInventory($db){
  $app = \Slim\Slim::getInstance();
  $printId=$app->request->post('print-id');
  $cleanPrintId = filter_var($printId, FILTER_SANITIZE_NUMBER_INT);
  $printName=$app->request->post('print-name');
  $cleanPrintName = filter_var($printName, FILTER_SANITIZE_STRING);
  $oPrint = new Prints();
  $updated=$oPrint->removeInventory($db,$cleanPrintId);
  if ($updated){
     $app->flash('success', 'A obra '.$cleanPrintName.' foi removida do Inventário com sucesso');
  }else{
    $app->flash('fail', 'Algo correu mal! Tente de novo');
  }
  $app->redirect('inventory');
}

function login($db){
  $app = \Slim\Slim::getInstance();
  $username=$app->request->post('username');
  $cleanUsername = filter_var($username, FILTER_SANITIZE_STRING);
  $password=$app->request->post('password');
  $cleanPassword = filter_var($password, FILTER_SANITIZE_STRING);
  $oUser = new Users();
  $validation=$oUser->validatePassword($db,$cleanUsername,$cleanPassword);
  if (!empty($validation)){
      $_SESSION['login'] = 1;
      $app->redirect('index');
  }else{
    $app->flash('fail-login', 'Password ou Username errados!');
    $app->redirect('login');
    $_SESSION['login'] = 0;
  }
}

function loggedIn(){
  $app = \Slim\Slim::getInstance();
  if(isset($_SESSION['login'])){
    if($_SESSION['login']!=1){
      $app->redirect('login');
    }
  }else{
    $_SESSION['login']=0;
    $app->redirect('login');
  }
}

function logout(){
  $app = \Slim\Slim::getInstance();
  $_SESSION['login']=0;
  $app->redirect('login');
}

$app->get('/', function() use($app,$db){
  loggedIn();
  $oArtist = new Artist();
  $artists=$oArtist->getAllArtists($db,"");
  $app->render('index.php' , array("artists" => $artists)); 
})->name('home');

$app->get('/index', function() use($app,$db){
  loggedIn();
  $oArtist = new Artist();
  $artists=$oArtist->getAllArtists($db,"");
  $app->render('index.php' , array("artists" => $artists)); 
})->name('home');

$app->get('/prints', function() use($app,$db){
  loggedIn();  
  $oPrint = new Prints();
  $randomPrints=$oPrint->getAllPrintsRandom($db);
  $app->render('prints.php', array("randomPrints" => $randomPrints)); 
})->name('prints');

$app->get('/inventory', function() use($app,$db){
  loggedIn();  
  $oPrint = new Prints();
  $randomPrints=$oPrint->getAllPrintsRandom($db);
  $app->render('inventory.php', array("randomPrints" => $randomPrints)); 
})->name('inventory');

$app->get('/login', function() use($app,$db){
  if($_SESSION['login']==1){
    $app->redirect('index');
  }
  $app->render('login.php'); 
})->name('login');

$app->get('/artist', function() use($app,$db){
  loggedIn();
  $id = $app->request->get('id');
  $cleanArtistId = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
  $oArtist = new Artist();
  $oPrint = new Prints();
  $artist=$oArtist->getArtistbyID($db,$cleanArtistId,"all");
  $prints=$oPrint->getPrintsByArtistID($db,$cleanArtistId);
  $app->render('artist.php' , array(
    "artist" => $artist, 
    "prints" => $prints
  )); 
})->name('artist');

$app->post('/', function() use($app, $db){
  $logout=$app->request->post('logout');
  if($logout=1){
    logout();
  }
  $operation=$app->request->post('artist-operation');
  if($operation=="search"){
    search($db);
  }else if($operation=="add-artist"){
    addArtist($db);
  }else if($operation=="delete-artist"){

  }else{
    $app->render('index.php' , array("artists" => $artists)); 
  }
})->name("index_post");

$app->post('/index', function() use($app, $db){
  $logout=$app->request->post('logout');
  if($logout==1){
    logout();
  }
  $operation=$app->request->post('artist-operation');
  if($operation=="search"){
    search($db);
  }else if($operation=="add-artist"){
    addArtist($db);
  }else if($operation=="delete-artist"){
    deleteArtist($db);
  }
})->name("index_post");

$app->post('/artist', function() use($app, $db){
  $logout=$app->request->post('logout');
  if($logout==1){
    logout();
  }
  $operation=$app->request->post('print-operation');
  if($operation=="add-print"){
    addPrint($db);
  }else if($operation=="delete-print"){
    deletePrint($db);
  }
})->name("artist_post");

$app->post('/prints', function() use($app, $db){
  $logout=$app->request->post('logout');
  if($logout==1){
    logout();
  }
  $operation=$app->request->post('print-operation');
  if($operation=="search"){
    search($db);
  }else if($operation=="add-inventory"){
    addInventory($db);
  }
})->name("prints_post");

$app->post('/inventory', function() use($app, $db){
  $logout=$app->request->post('logout');
  if($logout==1){
    logout();
  }
  $operation=$app->request->post('inventory-operation');
  if($operation=="search"){
    search($db);
  }else if($operation=="remove-inventory"){
    removeInventory($db);
  }
})->name("inventory_post");

$app->post('/login', function() use($app, $db){
  login($db);
})->name("inventory_post");



$app->run();