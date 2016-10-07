<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Myanimelist poll results parser</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/css/materialize.min.css">
    <link rel="stylesheet" href="assets//css/style.css" media="screen" title="no title">
</head>

<body>
    <div class="container">
        <h1>Mal poll parser</h1>



        <ul class="collapsible" data-collapsible="accordion">
            <li>
                <div class="collapsible-header">How does it works ?</div>
                <div class="collapsible-body">
                    <div class="row instructions">
                       <div class="col s12 m12">
                         <div class="card blue-grey darken-1">
                           <div class="card-content white-text">
                             <h3>Instructions</h3>
                             <p>
                                 Click on one of the anime links (season or top) or see results for a random anime like this :<br>
                             --> take the {anime id}/{anime name} in the url of the anime page in MAL (ex "30015/ReLIFE") and put it after the url (ex "anime.php?anime=30015/ReLIFE")<br><br>
                                 You can then add 1 or more filters like this : "&{filter}={value}" after the url</p>

                                 <h3 class="filters">Filters</h3>
                                 <p>
                                 -- Mendatory --<br>

                                 - anime --> take the {anime id}/{anime name} in the url of the anime page (ex 30015/ReLIFE)<br><br>


                                 -- Optional --<br>

                                 - length --> how many episodes (from ep 1) you want to display (default = none)<br>
                                 - graph --> 0 = no graph, only array / 1 = show graph (default = 1)<br>
                                 - sort (if graph = 0) --> 0 = sort in episode number / 1 = sort by best episodes (default = 1)<br>
                                 - detail -> 0 = don't show ratings (1 to 5) by episode / 1 = show... (default = 1)<br>
                             </p>
                           </div>
                         </div>
                       </div>
                     </div>
                </div>
            </li>
        </ul>

        <?
        require_once 'simple_html_dom.php';

        $url = 'https://myanimelist.net/anime/season/2016/summer';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $str = curl_exec($curl);
        curl_close($curl);
        $html = str_get_html($str);

        $url2 = 'https://myanimelist.net/topanime.php?type=tv';
        $curl2 = curl_init();
        curl_setopt($curl2, CURLOPT_URL, $url2);
        curl_setopt($curl2, CURLOPT_RETURNTRANSFER, 1);
        $str2 = curl_exec($curl2);
        curl_close($curl2);
        $html2 = str_get_html($str2);

        // Which html element we want to select
        $node1 = '.season #myanimelist #contentWrapper .js-categories-seasonal .seasonal-anime .title .title-text a.link-title';
        $node2 = '#myanimelist  div#content table.top-ranking-table .ranking-list .detail a.hoverinfo_trigger'

        ?>
        <div class="row">
            <div class="col s12 m6">
            <h2>Current season</h2>
            <?
            foreach($html->find($node1) as $element)
               echo '<a href="anime.php?anime='. substr($element->href, 30). '">' . $element->innertext . '</a><br>';
            ?>
            </div>

            <div class="col s12 m6">
            <h2>Top 50</h2>
        <?
        foreach($html2->find($node2) as $element)
           echo '<a href="anime.php?anime='. substr($element->href, 30). '">' . $element->innertext . '</a><br>';
        ?>
            </div>
        </div>

        </div>
    </body>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/js/materialize.min.js"></script>
  <script src="assets/js/script.js"></script>

</html>
