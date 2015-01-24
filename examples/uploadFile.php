<!DOCTYPE HTML>
<html>
<head>
    <title>Constant Contact API v2 Upload File Example</title>
    <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>

<!--
README: Add or update contact example
This example flow illustrates how a Constant Contact account owner can upload a file to their Library. In order for this example to function
properly, you must have a valid Constant Contact API Key as well as an access token. Both of these can be obtained from
http://constantcontact.mashery.com.
-->

<?php
// require the autoloaders
require_once '../src/Ctct/autoload.php';
require_once '../vendor/autoload.php';

use Ctct\ConstantContact;

// Enter your Constant Contact APIKEY and ACCESS_TOKEN
define("APIKEY", "ENTER YOUR API KEY");
define("ACCESS_TOKEN", "ENTER YOUR ACCESS TOKEN");

$cc = new ConstantContact(APIKEY);

if ($_FILES) {
    $fileName = $_POST['file_name'];
    $description = $_POST['description'];
    $folderId = $_POST['folder'];
    $fileLocation = $_FILES['file']['tmp_name'];

    $uploadStatusId = $cc->libraryService->uploadFile(ACCESS_TOKEN, $fileName, $fileLocation, $description, "MyComputer", $folderId);
    $fileUploadStatus = $cc->libraryService->getFileUploadStatus(ACCESS_TOKEN, $uploadStatusId);
}

$folders = array();
$params = array();
$next = null;
do {
    if ($next) {
        $params = array("next" => $next);
    }
    $foldersResult = $cc->libraryService->getLibraryFolders(ACCESS_TOKEN, $params);
    foreach ($foldersResult->results as $folder) {
        array_push($folders, $folder);
    }
    $next = $foldersResult->next;
} while ($next);
?>

<body>
<div class="well">
    <h3>Upload a New Image or PDF</h3>

    <form class="form-horizontal" name="submitFile" id="submitFile" method="POST" action="uploadFile.php" enctype="multipart/form-data">
        <div class="control-group">
            <label class="control-label" for="file_name">File Name</label>

            <div class="controls">
                <input type="text" id="file_name" name="file_name" placeholder="File Name">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="file">File</label>

            <div class="controls">
                <input type="file" id="file" name="file" placeholder="Choose File">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="description">Description</label>

            <div class="controls">
                <input type="text" id="description" name="description" placeholder="Description">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="folder">Folder</label>

            <div class="controls">
                <select name="folder">
                    <option value="0">Images</option>
                    <?php
                    foreach ($folders as $folder) {
                        echo '<option value="' . $folder->id . '">' . $folder->name . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">
                <div class="controls">
                    <input type="submit" value="Submit" class="btn btn-primary"/>
                </div>
        </div>
    </form>
</div>

<?php
// print the contents of the file upload status to screen
if (isset($fileUploadStatus)) {
echo '<span class="label label-success">File Uploaded!</span>';
echo '<div class="container alert-success"><pre class="success-pre">';
    foreach ($fileUploadStatus as $status) {
        print_r($status);
    }
    echo '</pre></div>';
}
?>

</body>
</html>