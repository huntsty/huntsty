<div class="clearfix"></div>
<div id="chart_div" style="width: 900px; height: 500px;"></div>
<div class="clearfix"></div>

<script
	type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Subscribers',	'List'],
          <?php foreach($arrList as $list):?>
          ['<?php echo $list['name']?>', <?php echo $list['stats']['member_count']?>],
         <?php endforeach;?>
        ]);

        var options = {
          title: '<?php _e('List subscribers', 'nm_mailchimp_plugin')?>',
          hAxis: {title: '<?php _e('Showing number of subscribers against each list', 'nm_mailchimp_plugin')?>', titleTextStyle: {color: 'green'}}
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>