<?php session_start(); 

require 'vendor/autoload.php';

use Aws\CloudWatch\CloudWatchClient;
use Aws\Rds\RdsClient;

$watch = CloudWatchClient::factory(array(
    'version' => 'latest',
    'region'  => 'us-west-2'
));

$client = RdsClient::factory(array(
        'version' => 'latest',
        'region'  => 'us-west-2'
));
$source = $_GET['source'];

$prefix = 'itmo-544-mp2';

$dimensions = array(
    array('Name' => 'QueueName', 'Value' => $prefix),
);

if($source == 1){
    
     $sqsresult = $watch->getMetricStatistics(array(
         'Namespace'  => 'AWS/SQS',
         'MetricName' => 'NumberOfMessagesSent',
         'Dimensions' => $dimensions,
         'StartTime'  => strtotime('-1 days'),
         'EndTime'    => strtotime('now'),
         'Period'     => 300,
         'Statistics' => array('Average'),
     ));
     echo json_encode($sqsresult['Datapoints']);
 }

?>

<!DOCTYPE html>
<html>
        <head>
                <title>Dashboard Page</title>
                <meta charset="utf-8">
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" cros
sorigin="anonymous">
                <style>
                        body {
                                background-image: url("aws_back.jpg");
                                background-repeat: no-repeat;
                        }
                        .first_div{
                                position: relative;
                                height: 190px;
                                text-align: center;
                                padding-top: 70px;
                        }
                        .second_div{
                                padding-bottom: 30px;
                                text-align: center;
                        }
                        a{
                                margin-left: 65px;
                                margin-right: 65px;
                        }
                </style>
        </head>
        <body>
                <div class="first_div">
                        <h1>Welcome to the cloud-native application!</h1>
                </div>
                <div class="second_div">
                        <a href="index.php" class="btn btn-primary">Home</a>
                        <a href="gallery.php" class="btn btn-primary">Gallery</a>
                        <a href="submit.php" class="btn btn-primary">Submit</a>
                </div>
                <div>
                        <h1>SQS Number of Messages Sent</h1>
                        <div id="sqs_message_sent"></div>
                </div>
                

            <script type="text/javascript">
                function line_chart(tag, url, xname, yname) {
    
                var chart = c3.generate({
                    bindto: tag,
                    data: {
                        url: url,
                        mimeType: 'json',
                        //x: 'Timestamp',
                        xFormat: '%Y-%m-%dT%H:%M:%S+00:00',
                        keys: {
                            x: xname, // it's possible to specify 'x' when category axis
                            value: [yname]
                        }
                    },
                    axis: {
                        x: {
                            type: 'timeseries',
                            tick: {
                                format: '%Y-%m-%d %H:%M'
                            }
                        }
                    }
                });
                return chart;
                }
var sqs_message_sent = line_chart('#sqs_message_sent', '?source=1', 'Timestamp', 'Average');
// var sqs_visible_chart = line_chart('#sqs_visible_chart', '/datasource.php?source=2', 'Timestamp', 'Average');
// var sqs_not_visible_chart = line_chart('#sqs_not_visible_chart', '/datasource.php?source=3', 'Timestamp', 'Average');
// var ec2_cpu_utilization = line_chart('#ec2_cpu_utilization', '/datasource.php?source=4', 'Timestamp', 'Average');
// var ec2_disk_writeops = line_chart('#ec2_disk_writeops', '/datasource.php?source=5', 'Timestamp', 'Average');
// var ec2_disk_read_bytes = line_chart('#ec2_disk_read_bytes', '/datasource.php?source=6', 'Timestamp', 'Average');
// var ec2_network_out = line_chart('#ec2_network_out', '/datasource.php?source=7', 'Timestamp', 'Average');
// var ec2_disk_read_ops = line_chart('#ec2_disk_read_ops', '/datasource.php?source=8', 'Timestamp', 'Average');
// var ec2_network_in = line_chart('#ec2_network_in', '/datasource.php?source=9', 'Timestamp', 'Average');
// var ec2_disk_write_bytes = line_chart('#ec2_disk_write_bytes', '/datasource.php?source=10', 'Timestamp', 'Average');
</script>


        </body>
</html>