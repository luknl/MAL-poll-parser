<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Myanimelist poll results parser</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/css/materialize.min.css">
    <link rel="stylesheet" href="assets//css/style.css" media="screen" title="no title">


    <?
    require_once 'simple_html_dom.php';

    print_r('<div class="container">');
    // Anime episode URL
    $animeId = $_GET['anime'];
    $anime = 'https://myanimelist.net/anime/' . $animeId . '/episode';

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
    if (!isset($_GET['length']) || empty($_GET['length'])) {
        $length = $length = (count($html->find($node1))/2); // uncomment the line to see the results for all the episodes but might be too long
    }
    else{
        $length = $_GET['length']; // Change length to low number for tests, otherwise parsing is way to long for animes with ep > 10
    }

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

       if (!isset($_GET['detail']) || $_GET['detail'] == '1') {
           ?>
           <ul class="collapsible" data-collapsible="accordion">
               <li>
                   <div class="collapsible-header">Detailed</div>
                   <div class="collapsible-body">
                       <?
                       print_r('<p>'.$episode.'</p>');
                       //Only way I found to display episode scores is with those weird arrays
                       print_r('<p>5 stars : '.$arr[2].' / '.$arr[3].'</p>');
                       print_r('<p>4 stars : '.$arr[6].' / '.$arr[7].'</p>');
                       print_r('<p>3 stars : '.$arr[10].' / '.$arr[11].'</p>');
                       print_r('<p>2 stars : '.$arr[14].' / '.$arr[15].'</p>');
                       print_r('<p>1 star : '.$arr[18].' / '.$arr[19].'</p>');
                       ?>
                   </div>
               </li>
           </ul>
           <?
       }
       else if ($_GET['detail'] == '0'){
       }

       $arr2[$i] = $arr;
       $arr = [];
    }

    ////// Results //////
    print_r('<br><h3>Results</h3>');

    $tab_score = array();

    if(!isset($_GET['graph']) || $_GET['graph'] == '1'){
        for($j=0; $j < $length; $j++){
           // Don't display the % after number with substr
           $tab_score[$j] = substr($arr2[$j][3], 0, -1);
        }
    }

    else if($_GET['graph'] == '0'){
        for($j=0; $j < $length; $j++){
           // Don't display the % after number with substr
           $substr = substr($arr2[$j][3], 0, -1);
           $tab_score[$j] = ($substr).': Episode '.($j+1); // can't seem to display $j+1 before $substr
        }

        //Sort array by descending scores, then display them
        if (!isset($_GET['sort']) || $_GET['sort'] == '1') {
            array_multisort($tab_score, SORT_DESC, SORT_NUMERIC);
        }
        else if ($_GET['sort'] == '0'){
        }
        for($k=0; $k < $length; $k++){
           print_r('<p>'.$tab_score[$k].'</p>');
        }
    }
    print_r('</div>');
    ?>
</head>



<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      ['Episode', 'Rating'],
      <?for($k=0; $k < $length-1; $k++){

        ?>[<? print_r($k+1);?>,  <? print_r($tab_score[$k]);?>],<?
      }
      ?>
      [<? print_r($length);?>,  <? print_r($tab_score[($length-1)]);?>]
    ]);

    var options = {
        curveType: 'line',
        legend: { position: 'none' },
        hAxis: {
            title: 'Episode',
            ticks: [<?for($k=0; $k < $length; $k++){
                print_r($k+1);?>,<?
            }?>]
        },
        vAxis: {
            title: '5 stars vote (%)',
            basline:0,
            maxValue:100,
            minValue:0
        },
    };

    var chart = new google.visualization.LineChart(document.getElementById('chart'));

    chart.draw(data, options);
  }
</script>
</head>
<body>
<div id="chart" style="width: 900px; height: 500px" style="margin:auto;"></div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/js/materialize.min.js"></script>
<script src="assets/js/script.js"></script>
</html>
