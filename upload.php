<?php
include_once 'config.php';

$response['code'] = "200";
if ($_FILES['file']['error'] != 4) {
// get local file
$fileContent = file_get_contents($_FILES["file"]["tmp_name"]);
//NOTA: si la 'carpeta' o el 'árbol' no existe, ¡se creará automáticamente!
$cloudPath = $_FILES["file"]["name"];

$isSucceed = uploadFile($fileContent, $cloudPath);

if ($isSucceed == true) {
  $response['msg'] = 'SUCCESS: to upload ' . $cloudPath . PHP_EOL;
  // Obtenga detalles del objeto (, contentType, updated [date], etc.)
  $response['data'] = getFileInfo($cloudPath);
} else {
  $response['code'] = "201";
  $response['msg'] = 'FAILED: to upload ' . $cloudPath . PHP_EOL;
}
}
header("Content-Type:application/json");
echo json_encode($response);
exit();
