<?php session_start();

header('Location: gallery.php');

require 'vendor/autoload.php';

use Aws\Rds\RdsClient;

$email=$_POST['email'];
$phone=$_POST['phone'];

$client = RdsClient::factory(array(
        'version' => 'latest',
        'region'  => 'us-west-2'
));

$s3 = new Aws\S3\S3Client([
    'version' => 'latest',
    'region'  => 'us-west-2'
]);

$result = $client->describeDBInstances(array(
    'DBInstanceIdentifier' => 'itmo-544-mp1',
));

$address = $result['DBInstances'][0]['Endpoint']['Address'];

$conn = mysqli_connect($address,"aerande","ilovebunnies","db1","3306") or die("Error " . mysqli_error($link));

if(mysqli_connect_errno()) {
        printf("Connection failed: %s\n", mysqli_connect_error());
        exit();
}

$name=$_FILES["photo"]["name"];
$tmp=$_FILES['photo']['tmp_name'];
$resultput = $s3->putObject(array(
             'Bucket'=>'rawimagesbucket',
             'Key' =>  $name,
             'SourceFile' => $tmp,
             'region' => 'us-west-2',
             'ACL'    => 'public-read'
        ));

$raw_uri = $resultput['ObjectURL'];

$checkimgformat=substr($raw_uri, -3);
if($checkimgformat == 'png' || $checkimgformat == 'PNG'){
    $image_raw=imagecreatefrompng($raw_uri);
}
else{
    $image_raw = imagecreatefromjpeg($raw_uri);
}

if($image_raw && imagefilter($image_raw, IMG_FILTER_GRAYSCALE))
{
    $gray_uploaddir = '/tmp_grayscale/';
    $gray_uploadfile = $gray_uploaddir .  basename($_FILES['photo']['name']);
    imagepng($image_raw, $gray_uploadfile);
}
else
{
    echo 'Conversion to grayscale failed.';
}

$resultimg = $s3->putObject(array(
    'Bucket' => 'finishedimagesbucket',
    'Key'    =>  basename($_FILES['photo']['name']),
    'SourceFile' => $gray_uploadfile,
    'ACL' => 'public-read'
));

$finishedurl=$resultimg['ObjectURL'];

imagedestroy($image_raw);

$sql = "CREATE TABLE IF NOT EXISTS records
(
id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
email VARCHAR(32),
phone VARCHAR(32),
s3_raw_url VARCHAR(200),
s3_finished_url VARCHAR(200),
status INT(1),
receipt BIGINT
)";

$create_table = $conn->query($sql);

if(!($create_table)){
    echo "table insertion error";
}

$stmt = "INSERT INTO records (email,phone,s3_raw_url,s3_finished_url,status,receipt) VALUES ('$email','$phone','$raw_uri','$finishedurl',0,651615)";

if($conn->query($stmt) === FALSE){
    echo "data insert error";
}

$stmt->close();
$conn->close();

?>