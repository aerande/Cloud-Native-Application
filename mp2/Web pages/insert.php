<?php session_start();

require 'vendor/autoload.php';

use Aws\Rds\RdsClient;
use Aws\Sqs\SqsClient;

$email=$_POST['email'];
$phone=$_POST['phone'];

echo $email;
echo $phone;

$sqsclient = SqsClient::factory(array(
        'version' => 'latest',
        'region'  => 'us-west-2'
));

$client = RdsClient::factory(array(
    'version' => 'latest',
    'region'  => 'us-west-2'
));

$s3 = new Aws\S3\S3Client([
    'version' => 'latest',
    'region'  => 'us-west-2'
]);

$result = $client->describeDBInstances(array(
    'DBInstanceIdentifier' => 'itmo-544-mp2',
));

$address = $result['DBInstances'][0]['Endpoint']['Address'];

echo $address;

$conn = mysqli_connect($address,"aerande","ilovebunnies","db1","3306") or die("Error " . mysqli_error($link));

if(mysqli_connect_errno()) {
        printf("Connection failed: %s\n", mysqli_connect_error());
        exit();
}

echo "connection";

$name=$_FILES["photo"]["name"];
$tmp=$_FILES['photo']['tmp_name'];
$resultput = $s3->putObject(array(
             'Bucket'=>'rawimagesbucket',
             'Key' =>  $name,
             'SourceFile' => $tmp,
             'region' => 'us-west-2',
             'ACL'    => 'public-read'
        ));

echo "raw input";

$raw_uri = $resultput['ObjectURL'];

$sql = "CREATE TABLE IF NOT EXISTS records
(
id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
email VARCHAR(32),
phone VARCHAR(32),
raw_url VARCHAR(200),
fin_url VARCHAR(200),
status INT(1),
receipt VARCHAR(200)
)";

$create_table = $conn->query($sql);

echo "table created";

if($create_table){
        echo "table is inserted";
}

else{
        echo "table error";
}

$finishedurl="YET_TO_UPLOAD";

$receipt=Md5($raw_uri);

$stmt = "INSERT INTO records (email,phone,raw_url,fin_url,status,receipt) VALUES ('$email','$phone','$raw_uri','$finishedurl',0,'$receipt')";

if($conn->query($stmt) === FALSE){
    echo "data insert error";
}

echo "data insert";

$listqueue = $sqsclient->listQueues([]);
$queueUrl = $listqueue['QueueUrls'][0];

$_SESSION['receipt_mp2']=$receipt;

echo $receipt;

$sqsclient->sendMessage(array(
    'QueueUrl'    => $queueUrl,
    'MessageBody' => $_SESSION['receipt_mp2']
));
echo $sqsclient;

echo "Done";

$stmt->close();
$conn->close();
?>