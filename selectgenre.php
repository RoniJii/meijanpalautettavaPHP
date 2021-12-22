<?php
require_once('headers.php');
require_once('./functions.php');

/* function openDB() {
    $db = new PDO('mysql:host=localhost;dbname=imdb;charset=utf8','root','');
    $db ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    return $db;
  }
   function returnError(PDOException $pdoex) {
    echo header('HTTP/1.1 500 Internal Server Error');
    $error = array('error' => $pdoex -> getmessage());
    print json_encode($error);
    exit;
  }
   */

   //haku ja kysely tietokantaan
  try {
      $db = openDb();
      $sql = "SELECT DISTINCT title_genres.genre
      FROM title_genres";
      $query = $db->query($sql);
      $results = $query->fetchAll(PDO::FETCH_ASSOC);
      print json_encode($results); 
      header('HTTP/1.1 200 OK');
      } catch (PDOException $pdoex) {
          returnError($pdoex);
  }