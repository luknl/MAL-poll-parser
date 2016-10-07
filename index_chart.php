<!DOCTYPE html>
<html>
  <head>
      <?php require_once "calcul.php"; ?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Episode', 'Rating'],
          <?for($k=0; $k < $length; $k++){

            ?>[<? print_r($k+1);?>,  <? print_r($tab_score[$k]);?>],<?
          }
          ?>
          [<? print_r($length);?>,  0]
        ]);

        var options = {
          curveType: 'line',
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart'));

        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="chart" style="width: 900px; height: 500px"></div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
  </body>
</html>
