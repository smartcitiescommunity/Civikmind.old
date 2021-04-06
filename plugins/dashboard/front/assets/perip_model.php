
<?php

$query_unk = "SELECT count(*) AS total
FROM `glpi_peripherals`
WHERE `is_deleted` = 0
AND `peripheralmodels_id` = 0
".$ent_peri." ";

$result = $DB->query($query_unk) or die('erro');
$unk = $DB->result($result,0,'total');


$query_os = "
SELECT glpi_peripheralmodels.name AS name, count( glpi_peripherals.id ) AS conta
FROM glpi_peripheralmodels, glpi_peripherals
WHERE glpi_peripherals.is_deleted =0
AND glpi_peripheralmodels.id = glpi_peripherals.peripheralmodels_id
".$ent_peri."
GROUP BY glpi_peripheralmodels.name
ORDER BY count( glpi_peripherals.id ) DESC ";

		
$result_os = $DB->query($query_os) or die('erro');

$arr_grf_os = array();

if($unk != 0) {
	$arr_grf_os[__('Unknown','dashboard')] = $unk;
}

while ($row_result = $DB->fetch_assoc($result_os))		
{ 
	$v_row_result = $row_result['name'];
	$arr_grf_os[$v_row_result] = $row_result['conta'];			
} 
	
$grf_os2 = array_keys($arr_grf_os);
$quant_os2 = array_values($arr_grf_os);

$conta_os = count($arr_grf_os);

echo "
<script type='text/javascript'>

$(function () {		
    	   		
		// Build the chart
        $('#graf_perip2').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: '".__('Peripherals by Model','dashboard')."'
            },
            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    size: '85%',
                     					dataLabels: {
								format: '{point.y} - ( {point.percentage:.1f}% )',
                   		style: {
                        	color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        		},
                        connectorColor: 'black'
                    },
                showInLegend: true
                }
            },
            series: [{
                type: 'pie',
                name: '',
                data: [
                    {
                        name: '" . $grf_os2[0] . "',
                        y: $quant_os2[0],
                        sliced: true,
                        selected: true
                    },";
                    
		for($i = 1; $i < $conta_os; $i++) {    
		     echo '[ "' . $grf_os2[$i] . '", '.$quant_os2[$i].'],';
		}                    
                                                         
		echo "      ]
            }]
        });
    });

		</script>"; 
		?>
