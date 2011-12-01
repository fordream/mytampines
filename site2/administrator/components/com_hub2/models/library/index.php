<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>Hello Windows Azure</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
</head>

<body>

<h1>Welcome to the world of Cloud Computing</h1>

<?php
$location =  (isset($_SERVER['USERDOMAIN']) && $_SERVER['USERDOMAIN'] == 'CIS') ? "remote Azure Cloud" : "local Development Fabric";
?>
<h5>Click <a href="/BlobSample.php">here</a> to perform Windows Azure Blob Storage Operations.</h5>
<h5>Click <a href="/TableSample.php">here</a> to perform Windows Azure Table Storage Operations in <?php print $location; ?>.</h5>
<h5>Click <a href="/SQLAzureSample.php">here</a> to perform Operations on SQL Azure.</h5>

<?php
$sXDrives = @getenv("X_DRIVES");
if ( ($sXDrives !== false) && !is_null($sXDrives) && is_string($sXDrives) && !empty($sXDrives) ) {
?>
<h5>Click <a href="/XDrives.php">here</a> to perform Operations on X-Drives.</h5>
<?php
}
?>

<h2>PHP Information</h2>
<p>
<?php phpinfo(); ?>
</p>

<hr/>

</body>
</html>