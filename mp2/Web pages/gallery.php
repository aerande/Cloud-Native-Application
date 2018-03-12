<?php session_start();
require 'vendor/autoload.php';
use Aws\Rds\RdsClient;
$client = RdsClient::factory(array(
        'version' => 'latest',
        'region'  => 'us-west-2'
));
$result = $client->describeDBInstances(array(
    'DBInstanceIdentifier' => 'itmo-544-mp2'
));
$address = $result['DBInstances'][0]['Endpoint']['Address'];
$conn = mysqli_connect($address,"aerande","ilovebunnies","db1","3306") or die("Error " . mysqli_error($link));
if(mysqli_connect_errno()) {
        printf("Connection failed: %s\n", mysqli_connect_error());
        exit();
}
$conn->real_query("SELECT * FROM test");
$res = $conn->use_result();
?>

<html>
        <head>
                <meta charset="utf-8">
                <title>Gallery Page</title>
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" cros
sorigin="anonymous">
                <style>
                        body {
                                background-image: url("aws_back.jpg");
                                background-repeat: no-repeat;
                        }
                        a{
                                margin-left: 65px;
                                margin-right: 65px;
                        }
                        .first_div{
                                position: relative;
                                height: 190px;
                                text-align: center;
                                padding-top: 70px;
                        }
                        .second_div{
                                padding-left:100px;
                                padding-right:100px;
                                text-align: center;
                        }
                </style>
        </head>
        <body>
                <div class="first_div">
                        <a href="index.php" class="btn btn-primary">Home</a>
                        <a href="gallery.php" class="btn btn-primary">Gallery</a>
                        <a href="submit.php" class="btn btn-primary">Submit</a>
                </div>
                <div class="second_div">
                        <?php
                                while ($row = $res->fetch_assoc()) {
                        ?>
                        <h6><strong>Email: </strong></h6><h6><?php echo $row['email']; ?></h6>
                        <h6><strong>Phone: </strong></h6><h6><?php echo $row['phone']; ?></h6>
                        <img src="<?php echo $row['raw_url']; ?>" alt="" width="600" height="400">
                        <img src="<?php echo $row['fin_url']; ?>" alt="" width="600" height="400">
                        <?php
                                }
                        ?>
                </div>
        </body>
</html>