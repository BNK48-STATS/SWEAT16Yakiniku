<?php
    $con= mysqli_connect("localhost","root","","yakiniku");
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    $date = mysqli_real_escape_string($con, $_POST['date']);
    $options = mysqli_real_escape_string($con, $_POST['options']);
    $final = array();
    echo '<script>
            Highcharts.chart(\'sgraph\', {
            chart: {
                type: \'line\'
            },
            title: {';
                if($options == 'views') {
                    echo "text: 'Sweat16! Yakiniku MV Graph (Views)'";
				} elseif ($options == 'likes') {
                    echo "text: 'Sweat16! Yakiniku MV Graph (Likes)'";
				} else if ($options == 'dislikes') {
                    echo "text: 'Sweat16! Yakiniku MV Graph (Dislikes)'";
                } else if ($options == 'comment') { 
                    echo "text: 'Sweat16! Yakiniku MV Graph (Comment)'";
                }
      echo '},
            subtitle: {
                text: \'Showing Data Date : '.$date.'\'
            },
            yAxis: {
				crosshair: true,
                title: {';
                    if($options == 'views') {
                        echo "text: 'Views'";
                    } elseif ($options == 'likes') {
                        echo "text: 'Likes'";
                    } else if ($options == 'dislikes') {
                        echo "text: 'Dislikes'";
                    } else if ($options == 'comment') { 
                        echo "text: 'Comments'";
                    }
            echo '},
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: true
                },
				scatter:{
                    lineWidth:2
                }
            },
            exporting: {
                    sourceWidth: 1920,
                    sourceHeight: 1080,
                // scale: 2 (default)
                chartOptions: {
                    subtitle: {
                        text: \'Source: SWEAT.BNK48-STATS.com\'
                    },
					title: {';
                        if($options == 'views') {
                            echo "text: 'Sweat16! Yakiniku MV Graph (Views)'";
                        } elseif ($options == 'likes') {
                            echo "text: 'Sweat16! Yakiniku MV Graph (Likes)'";
                        } else if ($options == 'dislikes') {
                            echo "text: 'Sweat16! Yakiniku MV Graph (Dislikes)'";
                        } else if ($options == 'comment') { 
                            echo "text: 'Sweat16! Yakiniku MV Graph (Comment)'";
                        }
			  echo '}
                }
            },
        ';
        $selecterer = "SELECT * FROM youtube_rank WHERE date = '$date'";
        $arrayList = [];
        $labels = [];
        $result = $con->query($selecterer);
        while($row = $result->fetch_assoc()) {
            $input = $row['name'];
            $labels[] = '"'.$row['time'].'"';
            if(!isset($arrayList[$input])) $arrayList[$input] = [];
            if($options == 'views') {
                $arrayList[$input][] = $row['views'];
            } elseif ($options == 'likes') {
                $arrayList[$input][] = $row['likes'];
            } else if ($options == 'dislikes') {
                $arrayList[$input][] = $row['dislike'];
            } else if ($options == 'comment') { 
                $arrayList[$input][] = $row['comment'];
            }
        }
        echo '
        xAxis: {
			crosshair: true,
            categories: ['.implode(',',$labels).']
        },
        dataGrouping: {
            enabled: false
        },
        series: [';
            foreach($arrayList as $member => $data){
                echo '{
                    name: "'.$member.'",
                    data: ['.implode(',',$data).'],
                },';
            }
                    echo '
                ]
            });
        </script>';
    ?>