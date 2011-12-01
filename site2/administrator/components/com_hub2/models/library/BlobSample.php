<?php
/**
 * Sample File BlobSample.php, bolierplate php sample file to get started with
 * blob operations with Windows Azure Storage Service using PHP Azure SDK.
 */

/**
 * Windows Azure provides storage in the cloud with authenticated access and triple
 * replication to help keep your data safe. Applications work with data using REST
 * conventions and standard HTTP operations to identify and expose data using URIs.
 * For more details on Windows Azure Storage Services, please visit the Windows Azure
 * Platform web-site: http://www.microsoft.com/windowsazure/windowsazure/
 *
 * Storage Account: All access to storage services takes place through the storage account.
 * The storage account is the highest level of the namespace for accessing each of the
 * fundamental services. It is also the basis for authentication.
 *
 * Step 0: Create Windows Azure Storage Account (http://www.microsoft.com/windowsazure/account/) and
 * configure it in Service configuraton file. When one creates PHP Windows Azure project in eclipse, two
 * new projects are created in PHP Explorer. First one is the service project and second one is the web role.
 *
 * This first service project contains two files: ServiceConfiguration.cscfg and ServiceDefinition.csdef file.
 * Please update the ServiceConfiguration.cscfg with the account name and key created on Windows Azure Portal.
 * These will go in settings named AzureCloudStorageAccountName and AzureCloudStorageAccountKey.
 *
 * For development purpose, WIndows Azure SDK provides Development Storage utility that simulates the Blob,
 * Queue, and Table Storage services available in the cloud. By default, Development storage has been configured with user
 * devstoreaccount1 and with key Eby8vdM02xNOcqFlqUwJPLlmEtlCDXJ1OUzFT50uSRZ6IFsuFq2UVErCz4I6tq/K1SZFPTOtr/KBHBeksoGMGw==
 * These Development Storage settings are defined in settings named AzureDevStorageAccountName and AzureDevStorageAccountKey
 * and one need not modify these settings.
 *
 * This ServiceConfiguration.cscfg should contain following settings,
 *
 * <?xml version="1.0" encoding="UTF-8"?>
 * <ServiceConfiguration xmlns="http://schemas.microsoft.com/ServiceHosting/2008/10/ServiceConfiguration" serviceName="WindowsAzureStorageExplorer">
 *   <Role name="WebRole">
 *     <ConfigurationSettings>
 *       <!-- Azure Storage Account for Cloud Environment -->
 *       <Setting name="AzureCloudStorageAccountName" value="MyAccountName"/>
 *       <Setting name="AzureCloudStorageAccountKey" value="MyAccountKey"/>
 *
 *       <!-- Azure Storage Account for Dev Environment with default account devstoreaccount1 -->
 *       <Setting name="AzureDevStorageAccountName" value="devstoreaccount1"/>
 *       <Setting name="AzureDevStorageAccountKey" value="Eby8vdM02xNOcqFlqUwJPLlmEtlCDXJ1OUzFT50uSRZ6IFsuFq2UVErCz4I6tq/K1SZFPTOtr/KBHBeksoGMGw=="/>
 *     </ConfigurationSettings>
 *     <Instances count="1"/>
 *   </Role>
 * </ServiceConfiguration>
 *
 * The corresponding ServiceDefinition.csdef should contain following settings,
 * <?xml version="1.0" encoding="UTF-8"?>
 * <ServiceDefinition xmlns="http://schemas.microsoft.com/ServiceHosting/2008/10/ServiceDefinition" name="WindowsAzureStorageExplorer">
 * <WebRole enableNativeCodeExecution="true" name="WebRole">
 *     <ConfigurationSettings>
 *       <!-- Azure Storage Account for Cloud Environment -->
 *       <Setting name="AzureCloudStorageAccountName"/>
 *       <Setting name="AzureCloudStorageAccountKey"/>
 *
 *       <!-- Azure Storage Account for Dev Environment -->
 *       <Setting name="AzureDevStorageAccountName"/>
 *       <Setting name="AzureDevStorageAccountKey"/>
 *     </ConfigurationSettings>
 *     <InputEndpoints>
 *       <InputEndpoint name="HttpIn" port="80" protocol="http"/>
 *     </InputEndpoints>
 *   </WebRole>
 * </ServiceDefinition>
 *
 * Now we are listing few common scenarios of using Windows Azure Storage Service using PHP Azure SDK.
 * PHP Azure SDK is an open source project to provide software development kit for Windows Azure and
 * Windows Azure Storage � Blobs, Tables & Queues. Refer http://phpazure.codeplex.com/ for details.
 */

/**
 * Add PHP Azure SDK to the PHP include_path. This SDK is avilable in Application Root directory.
 * This can be done by adding following set_include_path statement in
 * every PHP file refering PHP Azure SDK.
 *
 * Alternatively user can update the include_path in PHP.ini file.
 */
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__));

/**
 * Refer PHP Azure SDK library files for Azure Storage Services Operations
 */
require_once 'Microsoft/WindowsAzure/Storage.php';
require_once 'Microsoft/WindowsAzure/Storage/Blob.php';
//require_once dirname(__FILE__).'/Microsoft/WindowsAzure/Credentials.php';

try
{
    /**
     * Blob Service: The Blob service provides storage for entities, such as binary files
     * and text files. The REST API for the Blob service exposes two resources: containers
     * and blobs. A container is a set of blobs; every blob must belong to a container.
     * Please refer http://msdn.microsoft.com/en-us/library/dd573356.aspx for details.
     */
    echo '<h1>Windows Azure Blob Storage Operations</h1>';

    /**
     * Create Storage Client for blobs using account defined in ServiceConfiguration file
     */
    $blobStorageClient = createBlobStorageClient();
    if(!isBlobServiceAccessible($blobStorageClient))
    {
        return;
    }

    if (!$blobStorageClient->containerExists('uploads')) {

        $blobStorageClient->createContainer('uploads');

    }

    $blobStorageClient->setContainerAcl('uploads', Microsoft_WindowsAzure_Storage_Blob::ACL_PUBLIC_BLOB);

    if (isset($_GET['action']) && $_GET['action'] == 'Remove' && isset($_GET['name'])) {

        // Remove blob matching $_GET['name']

        $blobStorageClient->deleteBlob('uploads', $_GET['name']);

    } else {
        $azureLogoFile = ".\\WindowsAzure.jpg";
        $result = $blobStorageClient->putBlob('uploads', 'WindowsAzure.jpg',
        $azureLogoFile);
        echo "New blob with name '<b>" . $result->Name . "'</b>is created.<br /><br />";
        /** * Create another blob with name "WindowsAzure2.jpg" in
         $containerName container */
        $azureLogoFile = ".\\WindowsAzure.jpg";
        $result = $blobStorageClient->putBlob('uploads','WindowsAzure2.jpg', $azureLogoFile);
        echo "New blob with name '<b>" . $result->Name . "'</b>is created.<br /><br />";
    }

    // Fetch list of blobs

    $blobs = $blobStorageClient->listBlobs('uploads');

    // List blobs
    ?>
<table>
<?php
foreach ($blobs as $blob) {
    ?>

	<tr>

		<td><a href="<?php echo $blob->Url; ?>"><?php echo $blob->Name; ?></a></td>

		<td><?php echo $blob->LastModified; ?></td>

		<td><?php echo round($blob->Size / 1024 / 1024, 2); ?> MB</td>

		<td><a href="?action=Remove&name=<?php echo $blob->Name; ?>">remove</a></td>

	</tr>
	<?php } ?>
</table>
	<?php

	echo 'ok'; exit; /** * List all containers */
} catch (Exception $e) {
    print_r($e);
}

/**
 * Create Storage Client for blobs using account defined in ServiceConfiguration file
 *
 * @return Microsoft_WindowsAzure_Storage_Blob New storageclient for Azure Storage Blob
 */
function createBlobStorageClient()
{

    $host =
    Microsoft_WindowsAzure_Storage::URL_CLOUD_BLOB;
    $accountName = 'gypstorage'; // this will be a configuration parameter
    $accountKey = 'Ykdh2bOWhUIxHZ+BPSywxWtk2KaQoVszcOPHiCgem7BIIgyrPUmd/y6a39f7jy3LnuAJxrjHmjOuwSkuyxaJQg=='; // this will be a configuration parameter
    $usePathStyleUri = true;

    $blobStorageClient = new Microsoft_WindowsAzure_Storage_Blob( $host,
    $accountName, $accountKey );
    return $blobStorageClient;
}

/**
 * List all containers for specified account
 *
 * @param Microsoft_WindowsAzure_Storage_Blob $blobStorageClient Blob storage client
 * @return None
 */
function listContainers($blobStorageClient)
{
    echo "<b>List of Containers:<br/></b>";
    $containers = $blobStorageClient->listContainers();
    foreach ($containers as $container)
    {
        echo "- $container->Name <br/>";
    }
    echo "<br/>";
}

/**
 * List all blobs in specified container
 *
 * @param Microsoft_WindowsAzure_Storage_Blob $blobStorageClient Blob storage client
 * @param String $containerName Container Name
 * @return None
 */
function listBlobs($blobStorageClient, $containerName)
{
    echo "<b>List of blobs in container '" . $containerName . "': </b><br/>";
    $blobs = $blobStorageClient->listBlobs($containerName);
    foreach ($blobs as $blob)
    {
        echo " - $blob->Name " . "<br/>";
    }
    echo "<br/>";
}

/**
 * Create shared signature for specified blob in specified container
 *
 * @param String $containerName Container Name
 * @param String $blobName Blob Name
 * @param Time $startTime Validity Duration Start time (in Unix timestamp format)
 * @param Time $endTime Validity Duration End time (in Unix timestamp format)
 * @return None
 */
function createSharedSignature($containerName, $blobName, $startTime, $endTime)
{
    // Check for production / development environment and accordingly use Azure Storage account settings
    // This check will always use dev storage while running application in dev fabric and will use
    // cloud storage when running application in Windows Azure.
    //
    // If you want to use clould storage while running in dev fabric, uncomment the if condition check
    // and else-block. Just keep code within the if-block.
    //
    // Note: This is not 100% safe as a check.
    if (isset($_SERVER['USERDOMAIN']) && $_SERVER['USERDOMAIN'] == 'CIS')
    {
        $azureStorageAccountName = azure_getconfig('AzureCloudStorageAccountName');
        $azureStorageAccountKey = azure_getconfig('AzureCloudStorageAccountKey');
        $blobStorageHostName = Microsoft_WindowsAzure_Storage::URL_CLOUD_BLOB;
    }
    else
    {
        $azureStorageAccountName = azure_getconfig('AzureDevStorageAccountName');
        $azureStorageAccountKey = azure_getconfig('AzureDevStorageAccountKey');
        $blobStorageHostName = Microsoft_WindowsAzure_Storage::URL_DEV_BLOB;
    }

    // Create shared signature for blob that will be accesible from $startTime to $endTime
    $credentials = new Microsoft_WindowsAzure_SharedAccessSignatureCredentials(
    $azureStorageAccountName, $azureStorageAccountKey, false);

    $signature = $credentials->createSignature(
    $containerName . "/" . $blobName,
        'b',
        'r',
    isoDate($startTime), isoDate($endTime)
    );

    // Note: This is not 100% safe as a check.
    if (isset($_SERVER['USERDOMAIN']) && $_SERVER['USERDOMAIN'] == 'CIS')
    {
        return "http://$azureStorageAccountName.$blobStorageHostName/$containerName/" .
        $blobName . "?" . "st=" . urlencode(isoDate($startTime)) .
          "&se=" . urlencode(isoDate($endTime)) .
          "&sr=b&sp=r&sig=" . urlencode($signature);
    }
    else
    {
        return "http://$blobStorageHostName/$azureStorageAccountName/$containerName/" .
        $blobName . "?" . "st=" . urlencode(isoDate($startTime)) .
          "&se=" . urlencode(isoDate($endTime)) .
          "&sr=b&sp=r&sig=" . urlencode($signature);
    }
}

/**
 * Generate ISO 8601 compliant date string in UTC time zone
 *
 * @param Time $timestamp
 * @return String ISO 8601 representation specified timestamp
 */
function isoDate($timestamp = null)
{
    $tz = @date_default_timezone_get();
    @date_default_timezone_set('UTC');

    if (is_null($timestamp))
    $timestamp = time();

    $returnValue = str_replace('+00:00', 'Z', @date('c', $timestamp));
    @date_default_timezone_set($tz);
    return $returnValue;
}

/**
 * Determine if Windows Azure Blob Service accessible.
 *
 * @param object $blobStorageClient Windows Azure Blob Service client.
 *
 * @return boolean True if Blob Service is accessible.
 */
function isBlobServiceAccessible($blobStorageClient)
{
    $bSuccess = false;
    try
    {
        $blobStorageClient->listContainers();
        $bSuccess = true;
    }
    catch (Microsoft_WindowsAzure_Exception $ex)
    {
        echo "<p style='color: red'>Windows Azure Blob Service: Exception: \"{$ex->getMessage()}\"<p/>";
    }
    catch (Microsoft_Http_Transport_Exception $ex)
    {
        $location = (isset($_SERVER['USERDOMAIN']) && $_SERVER['USERDOMAIN'] == 'CIS')
        ? "Azure Cloud" : "Development Fabric";
        if( strpos($ex->getMessage(), 'cURL error occured during request for http://127.0.0.1') !== false ){
            echo "<p style='color: red'>Please check Blob Service status in {$location} Storage. Make sure that it is running!<p/>";
        }
    }
    catch (Exception $ex)
    {
        echo "<p style='color: red'>Unexpected Windows Azure Blob Service Exception: \"{$ex->getMessage()}\"<p/>";
    }

    return $bSuccess;
}
?>