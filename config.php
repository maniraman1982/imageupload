<?php
 
// Libreria GCS
require_once 'vendor/autoload.php'; 
use Google\Cloud\Storage\StorageClient;
$BUCKET_NAME = 'gemift_user_image_store';

// NOTE: to create private key JSON file: 
// https://console.cloud.google.com/iam-admin/serviceaccounts?project=  
$keyJson = '{
  "type": "service_account",
  "project_id": "gemift",
  "private_key_id": "c058ad9225ba4d81a5de2841c9ea023c3f983fb1",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCayzm5v5X4hyqP\nPHFRoh8GSqGRQ1QMMlJuub3zXjCmN4EYLbSIMrn3SYKxow1Ety1pMPQ8VHabXod+\nvi6y+emaREsledzVo3E8s9dpcRJ17mraoASZDoMl66l+5ssxCb2cTS4ULjs5pjr/\nru0ev1idqIRzUhJznLCnM5iVAeCZCh4Uh2E4hkTemFE43UHxWVVIyfdFfAYPKkvi\nJdXbO6yYInZmsprmFZAs5BRokLcScdLC8/XjGP+aTubXw6OumThdUL8QQukY11fL\nJmRSBAgEZAAxh7+qOoS8LjgBCA6AUMRcXXhd8Oti12UNWYvW51iBcYZgBcwZ9d06\nIaqi1QjfAgMBAAECggEADiyHt6N8ZwpxcjY1piTzNE9HJhcFLiy5cZwecZdxWvcA\nIielPUdv0d3wUrW8dEtVpCkwZAnT5nwsagWeHxNVOaQBQYg8GW/i4Y0SL3yEFTpk\nnGpEoYM7rRA8L/uQJYhfzV0f2Ac3sxquklUBL2yT/7O7lRexytZRMq49zX8jgr/O\n9K2ubM6WAhwNgO2rR4cWAG/MsiKaokH1+uoVN3VEIyTvOI/q8onqFCeuX9ayUacI\nIVCZNQnf/Jo0U2KRK4naN7E+A3TRfIW28LyjLNHkhBIPyQJ8xxed4NP7OLWZQg9V\n/hoLfriuQY4St1JnMEVrnTQITPDyhjVySj9C3alqMQKBgQDLFlwIqDDUgU/RLjjn\nkIKyeXG6Ic1RYBuGe17fByP07zSc3ea1PjsiYW+cPvKuzHhlAZxoFRvDkG9BJMBp\noqwZqrAydcNYGGKkmDjr5JCIDcZYJnTUvBmtlnkN1JZ6W+hkePakAxGnPlwa7/Km\nSkzk6G4QOM6OKTxc6k/eq/wPpwKBgQDDH8ID3R7XsuX5HDXbHc3/BIHImKRkXJ0z\ngtgxa22bvjaRltNXPRUPNy46qwAva3/xjvj/ggJPV83yVVY386bpphDMkCOjM+/3\nZG5DbMQG2rwJQudUJYa2c3tmzlgnrGx3t2AbQ98C3QNG0C7c5zqZtXCbGPT4yFPH\nkPrUlbMkCQKBgEwOs6kKVD9BroLIrMcMd+YfVVhAEITJWYWj7oPYSL8SVsHo2N5H\n0B2wH/yEFp8gNHafg9P3E87J6OCcwvLM2WdDZXmAYQg9GbRKzgaKMxbBEecxf8+s\nInHASNXFKBXrFb2LD9Oc0p+v2w67jkR4zxNreMzaeYEEMHcbyY27G0tBAoGBAIIh\n4AcrpbI9gdrN0a09B3GY0+Wwx5KXodAE56Kt/v/rsAEREgoQlsb7Cj3eZdU6YK39\nqFrfniLEcHm6KDJQsXUKaBHF376smNHpjRAyrdnUb6vNjvIzxNBR3G8IFwr+l6iQ\nB+ynK0iBlfnYRijVneN9eog18Msq75UdP+wfmsXhAoGAaa6Due0VU6GOBWBvuM0e\nl1rvjyJnTBw6g7YFlzQye8RSwt5aUOCC8i/6SaudTb2VqU30cHqEW9CcWEdIk/ZX\nYSjFaG7ucVeO9RjdfjDE0wj9Ki9IwraLWZe9sot04RIY11FDFYFGe1EYb7Df8nfg\nR0jRKO6CdMG9qzwLZ74lv/I=\n-----END PRIVATE KEY-----\n",
  "client_email": "gemift-storage-per-int@gemift.iam.gserviceaccount.com",
  "client_id": "104773245107833847636",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/gemift-storage-per-int%40gemift.iam.gserviceaccount.com"
}
';
$privateKeyFileContent = $keyJson;

function uploadFile($fileContent, $cloudPath) {
  $bucketName = $GLOBALS['BUCKET_NAME'];
  $privateKeyFileContent = $GLOBALS['privateKeyFileContent'];
  // conectarse a Google Cloud Storage utilizando una clave privada como autenticación
  try {
    $storage = new StorageClient([
      'keyFile' => json_decode($privateKeyFileContent, true)
    ]);
  } catch (Exception $e) {
    print $e;
    return false;
  }

  $bucket = $storage->bucket($bucketName);

  $storageObject = $bucket->upload(
    $fileContent,
    ['name' => $cloudPath]
  );

  return $storageObject != null;
}
 
function getFileInfo($cloudPath) {
  $bucketName = $GLOBALS['BUCKET_NAME'];
  $privateKeyFileContent = $GLOBALS['privateKeyFileContent'];
  try {
    $storage = new StorageClient([
      'keyFile' => json_decode($privateKeyFileContent, true)
    ]);
  } catch (Exception $e) {
    print $e;
    return false;
  }

  $bucket = $storage->bucket($bucketName);
  $object = $bucket->object($cloudPath);

  return $object->info();
}

function listFiles() {
  $bucketName = $GLOBALS['BUCKET_NAME'];
  $privateKeyFileContent = $GLOBALS['privateKeyFileContent'];
  try {
    $storage = new StorageClient([
      'keyFile' => json_decode($privateKeyFileContent, true)
    ]);
  } catch (Exception $e) {
    print $e;
    return false;
  }

  $result = array();
  $bucket = $storage->bucket($bucketName);

  foreach ($bucket->objects() as $object) {
    array_push(
      $result,
      array(
          'name' => $object->name(),
          'link' => 'https://storage.googleapis.com/'.$bucketName. '/' . $object->name()
        )
    );
  }

  return $result;
}