<?php   
 require_once('./headers.php');
 require_once('./functions.php');

/*  function openDB() {
  $db = new PDO('mysql:host=localhost;dbname=imdb;charset=utf8','root','');
  $db ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
  return $db;
}
 function returnError(PDOException $pdoex) {
  echo header('HTTP/1.1 500 Internal Server Error');
  $error = array('error' => $pdoex -> getmessage());
  print json_encode($error);
  exit;
} */

$input = json_decode(file_get_contents('php://input'));
$runtime = filter_var($input->runtime,FILTER_SANITIZE_STRING);
$avrating = filter_var($input->avrating,FILTER_SANITIZE_STRING);
$year = filter_var($input->year, FILTER_SANITIZE_STRING);
$genre = filter_var($input->genre, FILTER_SANITIZE_STRING);
//where titles.is_adult = $name
try {
    $db = openDb();
    $sql = "SELECT *
    FROM title_genres inner join titles 
    on title_genres.title_id = titles.title_id 
    INNER join aliases 
    on titles.title_id = aliases.title_id 
    INNER join title_ratings 
    on titles.title_id = title_ratings.title_id
    where title_genres.genre like '%$genre%' 
    and titles.start_year <= $year
    and aliases.region = 'FI' 
    and titles.runtime_minutes >= $runtime
    and title_ratings.average_rating > $avrating
    and title_ratings.num_votes > 1000  /* Alle tuhat karsitaan kaikki ns turhat/vähän arvioidut leffat pois */
    limit 10";
    $query = $db->query($sql);
    $results = $query->fetchAll(PDO::FETCH_ASSOC);
    print json_encode($results); 
    header('HTTP/1.1 200 OK');
    } catch (PDOException $pdoex) {
        returnError($pdoex);
}


/* $sql = "SELECT *
FROM title_genres inner join titles 
on title_genres.title_id = titles.title_id 
INNER join aliases 
on titles.title_id = aliases.title_id 
INNER join title_ratings 
on titles.title_id = title_ratings.title_id
where title_genres.genre like '%Action%' 
and titles.start_year <= $year 
and aliases.region = 'FI' 
and titles.runtime_minutes >= $name 
and title_ratings.average_rating < $writer 
and title_ratings.num_votes > 1000 
limit 10"; */


/* $db = openDb();
$sql = 'SELECT titles.primary_title 
FROM writers INNER JOIN titles
ON writers.title_id = titles.title_id
limit 5';
$query = $db->query($sql);
$results = $query->fetchAll(PDO::FETCH_ASSOC);
header('HTTP/1.1 200 OK');
print json_encode($results); */

/* 
SELECT titles.primary_title
from titles inner join directors
on titles.title_id = directors.title_id 
inner join names_
on directors.name_id = directors.name_id
where names_.name_ = "%Fred Astaire%"
limit 1 */


/* SELECT primary_title
FROM titles INNER JOIN title_genres
ON titles.title_id = title_genres.title_id 
WHERE genre LIKE "%Documentary%" 
limit 10;
*/

/* 
SELECT *
FROM title_genres inner join titles on title_genres.title_id = titles.title_id INNER join aliases on titles.title_id = aliases.title_id INNER join title_ratings on titles.title_id = title_ratings.title_id
where title_genres.genre like "%Action%" and titles.start_year < 2000 and aliases.region = "FI" and titles.runtime_minutes > 120 and title_ratings.average_rating < 5000 and title_ratings.num_votes > 1000 */