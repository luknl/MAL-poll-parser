<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Myanimelist poll results parser</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/css/materialize.min.css">
    <link rel="stylesheet" href="assets/css/style.css" media="screen" title="no title">
</head>

<body>
    <?require 'includes/header.php';?>
    <div class="container">
        <h3>MAL Poll Parser to Chart</h3>

        <ul class="collapsible" data-collapsible="accordion">
            <li>
                <div class="collapsible-header">How it works ?</div>
                <div class="collapsible-body">
                    <div class="row instructions">
                        <div class="col s12 m12">
                            <div class="card mal-blue base">
                                <div class="card-content white-text">
                                    <h5>Instructions</h5>
                                    <p>1) Choose an anime in the home page (jump to step 3) or choose your anime like in step 2<br><br>
                                    2) Take the {anime id}/{anime name} in the url of the anime page in MAL (ex "30015/ReLIFE") and put it after the url <br>(ex "anime.php?anime=30015/ReLIFE")<br><br>
                                    3) You can then add 1 or more filters like this : "&{filter}={value}" after the url<br>
                                    (ex "anime.php?anime=30015/ReLIFE&length=5&graph=0&sort=0")<br><br>
                                    The process is still very slow : 5 seconds by episode</p>


                                    <h5 class="filters">Filters</h5>
                                    <p>1) length --> how many episodes (from ep 1) you want to display (default = none, max = 25)<br>
                                    2) graph --> 0 = no graph, only array / 1 = show graph (default = 1)<br>
                                    3) sort (if graph = 0) --> 0 = sort in episode number / 1 = sort by best episodes (default = 1)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>

        <?
        require_once 'includes/simple_html_dom.php';

        $url = 'https://myanimelist.net/anime/season';
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
        $node2 = '#myanimelist  div#content table.top-ranking-table .ranking-list .detail a.hoverinfo_trigger';

        ?>
        <div class="row">
            <div class="col s12 m6">
                <h4>Current season</h4>
                <?
                foreach($html->find($node1) as $element)
                   echo '<a href="anime.php?anime='. substr($element->href, 30). '">' . $element->innertext . '</a><br>';
                ?>
            </div>

            <div class="col s12 m6">
                <h4>Top 50</h4>
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
