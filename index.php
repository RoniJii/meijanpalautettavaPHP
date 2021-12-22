<?php   
 require_once('./headers.php');
 require_once('./functions.php');

//inputtit ja niiden sanitointi
$input = json_decode(file_get_contents('php://input'));
$runtime = filter_var($input->runtime,FILTER_SANITIZE_STRING);
$avrating = filter_var($input->avrating,FILTER_SANITIZE_STRING);
$year = filter_var($input->year, FILTER_SANITIZE_STRING);
$genre = filter_var($input->genre, FILTER_SANITIZE_STRING);

//haku ja kysely tietokantaan
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
    and titles.runtime_minutes <= $runtime
    and title_ratings.average_rating <= $avrating
    and title_ratings.num_votes < 500  /* Alle tuhat karsitaan kaikki ns turhat/vähän arvioidut leffat pois */
    limit 10";
    $query = $db->query($sql);
    $results = $query->fetchAll(PDO::FETCH_ASSOC);
    print json_encode($results); 
    header('HTTP/1.1 200 OK');
    } catch (PDOException $pdoex) {
        returnError($pdoex);
}
