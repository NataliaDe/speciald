<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Enable Amazon S3 Storage
|--------------------------------------------------------------------------
|
| This option enable using Amazon S3 storage
|
*/

//$config['enable_amazon_s3'] = true;


/*
|--------------------------------------------------------------------------
| Enable MinIO S3 Storage
|--------------------------------------------------------------------------
|
| This option enable using MinIO S3 storage
|
| NOTE: you can enable only one storage Amazon OR MinIO !!!
|
*/

//$config['enable_minio_s3'] = false;


/*
|--------------------------------------------------------------------------
| Upload path
|--------------------------------------------------------------------------
|
| This option states upload path
|
*/

$config['upload_path'] = 'data';
$config['templates_path'] = 'data_templates';