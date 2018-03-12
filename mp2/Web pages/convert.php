<?php session_start();

require 'vendor/autoload.php';

use Aws\Rds\RdsClient;
use Aws\Sqs\SqsClient;
use Aws\Sns\SnsClient;

$sqsclient = SqsClient::factory(array(
    'version' => 'latest',
    'region'  => 'us-west-2'
));

$sns = new Aws\Sns\SnsClient([
    'version' => 'latest',
    'region'  => 'us-west-2'
]);

$client = RdsClient::factory(array(
        'version' => 'latest',
        'region'  => 'us-west-2'
));

$s3 = new Aws\S3\S3Client([
    'version' => 'latest',
    'region'  => 'us-west-2'
]);

$listqueue = $sqsclient->listQueues([]);
$queueUrl = $listqueue['QueueUrls'][0];

$result = $client->describeDBInstances(array(
    'DBInstanceIdentifier' => 'itmo-544-mp2',
));

$address = $result['DBInstances'][0]['Endpoint']['Address'];

echo $address;
$conn = mysqli_connect($address,"aerande","ilovebunnies","db1","3306") or die("Error " . mysqli_error($link));
echo "Suceesfully Connected";
$sqsresult = $sqsclient->receiveMessage(array(
    'QueueUrl' => $queueUrl,
    'VisibilityTimeout' => 300,
        'MaxNumberOfMessages' => 1
));

$messagebodyfromsqs=$sqsresult['Messages'][0]['Body'];
$receipttodelete=$sqsresult['Messages'][0]['ReceiptHandle'];

if(!empty($messagebodyfromsqs)){
        $sqlselect = "SELECT email,raw_url,fin_url FROM records where receipt='$messagebodyfromsqs'";
        $resultforselect = $conn->query($sqlselect);
        while($row= $resultforselect->fetch_assoc()){
                $rawurl=$row["raw_url"];
                $email=$row["email"];
        }
}

$checkimgformat=substr($rawurl, -3);

if($checkimgformat == 'png' || $checkimgformat == 'PNG'){
    $image_raw=imagecreatefrompng($rawurl);
}

else{
    $image_raw = imagecreatefromjpeg($rawurl);
}

if($image_raw && imagefilter($image_raw, IMG_FILTER_GRAYSCALE)) {
    $gray_uploaddir = '/tmp_grayscale/';
    $gray_uploadfile = $gray_uploaddir.basename($rawurl);
    imagepng($image_raw, $gray_uploadfile);
}
else {
    echo 'Conversion to grayscale failed.';
}

$resultimg = $s3->putObject(array(
    'Bucket' => 'finishedimagesbucket',
    'Key'    =>  basename($rawurl),
    'SourceFile' => $gray_uploadfile,
    'Region' => 'us-west-2',
    'ACL' => 'public-read'
));

$finishedurl=$resultimg['ObjectURL'];

imagedestroy($image_raw);

$stmt = "UPDATE records SET fin_url='$finishedurl',status=1 WHERE receipt='$messagebodyfromsqs'";
$resultforselect = $conn->query($stmt);

$listsns = $sns->listTopics([]);
$topicarn = $listsns['Topics'][0]['TopicArn'];

$result=$sns->subscribe(array(
        'TopicArn'=>$topicarn,
        'Protocol'=>'email',
        'Endpoint'=>$email
));

$sendmessage=$sns->publish(array(
      'Message' => 'Your image is processed. You can access the image at $finishedurl',
      'Subject' => 'mp2 project'
));

$result=$sqsclient->deleteMessage([
        'QueueUrl'=>$queueUrl,
        'ReceiptHandle'=>$receipttodelete
]);

$stmt->close();
$conn->close();
?>