
<?php

//echo '<div id="grafcat" class="span12" style="height: 460px; margin-top:40px;">';

$query4 = "
SELECT glpi_itilcategories.completename as cat_name, COUNT(glpi_tickets.id) as cat_tick
FROM glpi_tickets,  glpi_itilcategories
WHERE glpi_tickets.is_deleted = '0'
AND glpi_itilcategories.id = glpi_tickets.itilcategories_id
".$entidade."
GROUP BY glpi_itilcategories.id
ORDER BY `cat_tick` DESC
LIMIT 10 ";

$result4 = $DB->query($query4) or die('erro');

$arr_grf4 = array();
while ($row_result = $DB->fetch_assoc($result4)) {
	$v_row_result = $row_result['cat_name'];
	$arr_grf4[$v_row_result] = $row_result['cat_tick'];
}

$grf4 = array_keys($arr_grf4) ;
$quant4 = array_values($arr_grf4) ;
$soma4 = array_sum($arr_grf4);

$grf_3a = json_encode($grf4);
$quant_2a = implode(',',$quant4);


echo "
<script type='text/javascript'>

$(function () {
        $('#grafcat').highcharts({
            chart: {
                type: 'bar',
                height: 550
            },
            title: {
                text: 'Top 10 - ".__('Tickets by Category','dashboard')."'
            },

            xAxis: {
                categories: $grf_3a,
                labels: {
                    //rotation: -55,
                    align: 'right',
                    style: {
                        //fontSize: '11px',
                        //fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                }
            },
            /*tooltip: {
                headerFormat: '<span style=\"font-size:10px\">{point.key}</span><table>',
                pointFormat: '<tr><td style=\"color:{series.color};padding:0\">{series.name}: </td>' +
                    '<td style=\"padding:0\"><b>{point.y:.1f} </b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },*/
            plotOptions: {
                bar: {
                    pointPadding: 0.2,
                    borderWidth: 0,
                    borderWidth: 2,
                borderColor: 'white',
                shadow:true,
                showInLegend: false,
                }
            },
            series: [{
            	 colorByPoint: true, 
                name: '".__('Tickets','dashboard')."',
                data: [".$quant_2a."],
                dataLabels: {
                    enabled: true,
                    //color: '#000099',
                    align: 'center',
                    x: 20,
                    y: 0,
                    style: {
                       // fontSize: '13px',
                       // fontFamily: 'Verdana, sans-serif'
                    },
                    formatter: function () {
                    return Highcharts.numberFormat(this.y, 0, '', ''); // Remove the thousands sep?
                }
                }
            }]
        });
    });

		</script>";

		echo "</div>";
		 ?>
