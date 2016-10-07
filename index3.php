<!--Don't work for anime with more than 14 eps-->
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
$anime = 'https://myanimelist.net/anime/32182/Mob_Psycho_100/forum?topic=episode';

// Initilization of the second curl parsing page : http://myanimelist.net/anime/{anime_id}/{anime_name}/episode/{episode_number}
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $anime);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$str = curl_exec($curl);
curl_close($curl);
$html = str_get_html($str);

// Which html element we want to select
$node1 = 'body.page-common div#myanimelist div.wrapper div#contentWrapper div#content table tbody tr div.js-scrollfix-bottom-rel div.page-forum table#forumTopics tbody tr td.forum_boardrow1 a';
$node2 = 'body.page-common div#myanimelist div.wrapper div#contentWrapper div#content table tbody tr div.js-scrollfix-bottom-rel div.page-forum table#forumTopics tbody tr td.forum_boardrow2';
$node3 = 'body.page-forum div#myanimelist div.wrapper div#contentWrapper div#content div.forum_boardrow1 table tbody tr td';


// how many elements we found with $node1, which is the number of episodes (have to divide by 2 for mysterious reasons)
// $length = (count($html->find($node2))/2*10); // uncomment the line to see the results for all the episodes but might be too long
$length = 8; // Change length to low number for tests, otherwise parsing is way to long for animes with ep > 10
$length2 = $length/10;
$length3 = (count($html->find($node2))/2); // uncomment the line to see the results for all the episodes but might be too long


// Start for loop to get scores from all the episodes
for ($i = 0; $i < $length; $i+=10)
{

   // Initilization of the third curl parsing page : http://myanimelist.net/forum/?topicid={topic_id}&pollresults=1
   $forum_id = 'https://myanimelist.net'.($html->find($node1, $i)->href).'&pollresults=1';
   $curl2 = curl_init();
   curl_setopt($curl2, CURLOPT_URL, $forum_id);
   curl_setopt($curl2, CURLOPT_RETURNTRANSFER, 1);
   $str2 = curl_exec($curl2);
   curl_close($curl2);
   $html2 = str_get_html($str2);
   // print_r($html2->find($node2, $i)->href);

   // return all the values of the td found with $node2, which are all the scores of the episode vote
   foreach ($html2->find($node3) as $table)
   {
      $arr[] = trim($table->innertext);
   }

   //Display 0 if there is no vote
   if($arr[2] == ''){$arr[2] = 0;}
   if($arr[6] == ''){$arr[6] = 0;}
   if($arr[10] == ''){$arr[10] = 0;}
   if($arr[14] == ''){$arr[14] = 0;}
   if($arr[18] == ''){$arr[18] = 0;}


   print_r('<p>'.$html->find($node1, $i)->innertext.'</p>');
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
for($j=0; $j < $length; $j+=10)
{
   // Don't display the % after number with substr
   $substr = substr($arr2[$j][3], 0, -1);
   $tab_score[$j] = ($substr).': Episode '.($length3); // can't seem to display $j+1 before $substr
   $length3--;
}

//Sort array by descending scores, then display them
array_multisort($tab_score, SORT_DESC, SORT_NUMERIC);
for($k=0; $k < $length2; $k++){
   print_r('<p>'.$tab_score[$k].'</p>');
}
?>

   <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>

</body>

</html>
