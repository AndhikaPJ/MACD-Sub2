<?php

require_once 'vendor/autoload.php';
require_once "random_string.php";

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;
//Ganti [Account] dan [Key] dengan Data yang ada di Storage/Access Key
$connectionString = "DefaultEndpointsProtocol=https;AccountName=[Account];AccountKey=[Key];";

//Ganti BlobName dengan Nama Container di Storage/Blob
$containerName = "BlobName";

// Create blob client.
$blobClient = BlobRestProxy::createBlobService($connectionString);

if (isset($_POST['submit'])) {
	$fileToUpload = strtolower($_FILES["fileToUpload"]["name"]);
	$content = fopen($_FILES["fileToUpload"]["tmp_name"], "r");
	echo fread($content, filesize($fileToUpload));

	$blobClient->createBlockBlob($containerName, $fileToUpload, $content);
	header("Location: index.php");
}

$listBlobsOptions = new ListBlobsOptions();
$listBlobsOptions->setPrefix("");

$result = $blobClient->listBlobs($containerName, $listBlobsOptions);

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Submission 2 MACD">
	<meta name="author" content="">
	<title>Computer Vision Azure!</title>
	<link rel="icon" href="https://sqlvafkp24pamflmsg.blob.core.windows.net/blockblobshxjgdp/search.ico">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
	<div class="container mt-4">

		<h1 class="text-center">Submission 2 Menjadi Azure Cloud Dev!</h1>
		
		<div class="mt-4 mb-2">
			<form class="d-flex justify-content-center" action="index.php" method="post" enctype="multipart/form-data">
				<input type="file" name="fileToUpload" accept=".jpeg,.jpg,.png" required="">
				<input type="submit" name="submit" value="Upload">
			</form>
		</div>

		<h2>Jumlah File in Storage : <?php echo sizeof($result->getBlobs())?></h2>
		<table class='table table-hover'>
			<thead>
				<tr>
					<th>Nama File</th>
					<th>URL File</th>
					<th>Gaskeun</th>
				</tr>
			</thead>
			<tbody>
				<?php

				do {
					foreach ($result->getBlobs() as $blob)
					{
						?>
						<tr>
							<td><?php echo $blob->getName() ?></td>
							<td><?php echo $blob->getUrl() ?></td>
							<td>
								<form action="analisa.php" method="post">
									<input type="hidden" name="url" value="<?php echo $blob->getUrl()?>">
									<input type="submit" name="submit" value="Analisa!" class="btn btn-primary">
								</form>
							</td>
						</tr>
						<?php
					}
					$listBlobsOptions->setContinuationToken($result->getContinuationToken());
				} while($result->getContinuationToken());

				?>
			</tbody>
		</table>

	</div>
	
			<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
			<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
			<script src="https://getbootstrap.com/docs/4.0/assets/js/vendor/popper.min.js"></script>
			<script src="https://getbootstrap.com/docs/4.0/dist/js/bootstrap.min.js"></script>
	
</body>
</html>
