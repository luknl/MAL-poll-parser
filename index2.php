<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Myanimelist poll results parser</title>
</head>

<body>

<?
require_once 'simple_html_dom.php';

// Anime episode URL
$anime = 'https://myanimelist.net/anime/32182/Mob_Psycho_100/episode';

// Initilization of the second curl parsing page : http://myanimelist.net/anime/{anime_id}/{anime_name}/episode
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $anime);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$str = curl_exec($curl);
curl_close($curl);
$html = str_get_html($str);

// Which html element we want to select
$node1 = 'div#content table tbody tr td:nth-child(2) div.js-scrollfix-bottom-rel table tbody tr table tbody tr.episode-list-data td.episode-title a';
$node2 = 'body.anime_episode_detail div#myanimelist div.wrapper div#contentWrapper div#content table tbody tr div.js-scrollfix-bottom-rel div div table tbody td.forum_boardrow1 a';
$node3 = 'body.page-forum div#myanimelist div.wrapper div#contentWrapper div#content div.forum_boardrow1 table tbody tr td';


// how many elements we found with $node1, which is the number of episodes (have to divide by 2 for mysterious reasons)
// $length = (count($html->find($node1))/2); // uncomment the line to see the results for all the episodes but might be too long
$length = 6; // Change length to low number for tests, otherwise parsing is way to long for animes with ep > 10


// Start for loop to get scores from all the episodes
for ($i = 0; $i < $length; ++$i)
{
   $episode = ($html->find($node1, $i)->href);

   // Initilization of the second curl parsing page : http://myanimelist.net/anime/{anime_id}/{anime_name}/episode/{episode_number}
   $curl2 = curl_init();
   curl_setopt($curl2, CURLOPT_URL, $episode);
   curl_setopt($curl2, CURLOPT_RETURNTRANSFER, 1);
   $str2 = curl_exec($curl2);
   curl_close($curl2);
   $html2 = str_get_html($str2);


   // This url if the forum link + extensions to see poll results
   $forum_id = 'https://myanimelist.net'.($html2->find($node2, 0)->href).'&pollresults=1';

   // Initilization of the third curl parsing page : http://myanimelist.net/forum/?topicid={topic_id}&pollresults=1
   $curl3 = curl_init();
   curl_setopt($curl3, CURLOPT_URL, $forum_id);
   curl_setopt($curl3, CURLOPT_RETURNTRANSFER, 1);
   $str3 = curl_exec($curl3);
   curl_close($curl3);
   $html3 = str_get_html($str3);

   // return all the values of the td found with $node3, which are all the scores of the episode vote
   foreach ($html3->find($node3) as $table)
   {
      $arr[] = trim($table->innertext);
   }

   //Display 0 if there is no vote
   if($arr[2] == ''){$arr[2] = 0;}
   if($arr[6] == ''){$arr[6] = 0;}
   if($arr[10] == ''){$arr[10] = 0;}
   if($arr[14] == ''){$arr[14] = 0;}
   if($arr[18] == ''){$arr[18] = 0;}


   print_r('<p>'.$episode.'</p>');
   //Only way I found to display episode scores is with those weird arrays
   print_r('<p>5 stars : '.$arr[2].' / '.$arr[3].'</p>');
   print_r('<p>4 stars : '.$arr[6].' / '.$arr[7].'</p>');
   print_r('<p>3 stars : '.$arr[10].' / '.$arr[11].'</p>');
   print_r('<p>2 stars : '.$arr[14].' / '.$arr[15].'</p>');
   print_r('<p>1 star : '.$arr[18].' / '.$arr[19].'</p>');

   $arr2[$i] = $arr;
   $arr = [];
}

////// Results //////
print_r('<br><h1>Results</h1>');

$tab_score = array();
for($j=0; $j < $length; $j++)
{
   // Don't display the % after number with substr
   $substr = substr($arr2[$j][3], 0, -1);
   $tab_score[$j] = ($substr).': Episode '.($j+1); // can't seem to display $j+1 before $substr
}

//Sort array by descending scores, then display them
// array_multisort($tab_score, SORT_DESC, SORT_NUMERIC);
for($k=0; $k < $length; $k++){
   print_r('<p>'.$tab_score[$k].'</p>');
}
?>

   <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>

</body>

</html>
