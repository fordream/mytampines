<?php
/**
 * Sample File TableSample.php, bolierplate php sample file to get started with 
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
 * Add PHP Azure SDK to the PHP include_path. This SDK is avilable in Web Role Root directory. 
 * This can be done by adding following set_include_path statement in 
 * every PHP file refering PHP Azure SDK.
 * 
 * Alternatively user can update the include_path in PHP.ini file.
 */
set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["RoleRoot"] . "\\approot\\");

/**
 * Refer PHP Azure SDK library files for Azure Storage Services Operations
 */
require_once 'Microsoft/WindowsAzure/Storage.php';
require_once 'Microsoft/WindowsAzure/Storage/Table.php';

/**
 * Table Storage Service: TBD - Add some description
 */
echo '<h1>Windows Azure Table Storage Operations</h1>';

/**
 * Refer to SampleEntity class
 */
require_once "SampleEntity.php";  

try {
  /**
   * Create Storage Client for table using account defined in ServiceConfiguration file
   */ 
  $tableStorageClient = createTableStorageClient();
  if(!isTableServiceAccessible($tableStorageClient))
  {
    return;
  }

  /**
   * For cleanup, delete table "testtable" if exists
   */
  if ($tableStorageClient->tableExists("testtable"))
  {
    $tableStorageClient->deletetable("testtable");
        
    /**
     * Wait for 35s as this operations takes time. One can use sleep(35) to put current thread in sleep state,
     * but default execution timeout for PHP is 30s. Hence increase timeout if you want to use sleep.
     */
    echo "Deleting table <b>'testtable'</b>. Wait for about <b>35s</b> to execute sample again.<br/><br/>";
    return;
  }
  
  /**
   * List all tables
   */
  listTables($tableStorageClient);
  
  /**
   * Create a new Table with name "testtable"
   */
  $result = $tableStorageClient->createTable("testtable");
  echo "New table with name '<b>" . $result->Name . "'</b> is created. <br/><br/>";
  
  /**
   * Again get list all tables, showing new table we created in previous call
   */
  listTables($tableStorageClient);
          
  /**
   * Inserting an entity into Windows Azure Table "testtable"
   */
  $entity = new SampleEntity('partition1', 'row1');
  $entity->Name = "DefaultName";
  $entity->Age = 25;
  $entity->Visible = true;
  $result = $tableStorageClient->insertEntity('testtable', $entity);
  
  // Check the timestamp and etag of the newly inserted entity
  echo "New <b>entity</b> added at Timestamp '<b>" . $result->getTimestamp() . "</b>'<br/><br/>";
  
  /**
   * Retrieving an entity by partition key and row key
   */
  $retrivedEntity= $tableStorageClient->retrieveEntityById('testtable', 'partition1', 'row1', 'SampleEntity');
  echo "<b>Retrieved newly added entity:</b><br/>";
  var_dump($retrivedEntity);
  echo "<br/><br/>";  
  
  /**
   * Updating an entity
   */
  $retrivedEntity->Name = 'Soyatec';
  $result = $tableStorageClient->updateEntity('testtable', $retrivedEntity);
  echo "Entity updated with new Name as '<b>Soyatec</b>'<br/></br>";
  
  /**
   * Retrieving the updated entity
   */
  $retrivedEntity= $tableStorageClient->retrieveEntityById('testtable', 'partition1', 'row1', 'SampleEntity');
  echo "<b>Retrieved updated entity:</b><br/>";
  var_dump($retrivedEntity);
  echo "<br/><br/>";
      
  /**
   * Performing queries using a filter condition
   */
  $entities = $tableStorageClient->retrieveEntities(
                                    'testtable',
                                    'Name eq \'Soyatec\' and PartitionKey eq \'partition1\'',
                                    'SampleEntity'
                                    );
  
  echo "Result for filter query <b>" . 'Name eq \'Soyatec\' and PartitionKey eq \'partition1\'' . "</b>:<br/>";
  foreach ($entities as $entity)
  {
    echo ' - Name: ' . $entity->Name . "<br/>";
  }
  echo "<br/>";
  
  /**
   * Deleting an entity
   */
  $result = $tableStorageClient->deleteEntity('testtable', $retrivedEntity);
  echo "<b>Entity deleted.</b><br/><br/>";
  
  /**
   * Use the table entity group transaction features provided by Windows Azure 
   * table storage. Windows Azure table storage supports batch transactions on 
   * entities that are in the same table and belong to the same partition group. 
   * A transaction can include at most 100 entities.
   */
  // Start batch/transaction
  $batch = $tableStorageClient->startBatch();
  echo "<b>Started batch/transaction.</b><br/><br/>";
  
  // Prepare 5 random entities to be inserted in job
  $entities = array();
  for ($i = 0; $i < 5; $i ++) 
  {
    $entity = new SampleEntity('partition2', 'row_no_' . ($i + 1) );
    $entity->Name = 'Name_' . ($i + 1);
    $entity->Age = rand ( 1, 130 );
    $entity->Visible = true;
    
    $entities[] = $entity;
  }
  
  // Insert entities in batch
  foreach ($entities as $entity)
  {
    $tableStorageClient->insertEntity('testtable', $entity);
  }
  
  /**
   * Commit batch/transaction
   */ 
  $batch->commit();
  echo "<b>Committed batch/transaction.</b><br/><br>";
  
  /**
   * Performing queries to get all inserted rows in previous transaction
   */
  $entities = $tableStorageClient->retrieveEntities(
                                          'testtable',
                                          'PartitionKey eq \'partition2\'',
                                          'SampleEntity'
                                          );
  
  /*
   * Display inserted rows in previous transaction
   */
  echo "<b>Inserted following entities in previous transaction</b>:<br/>";
  foreach ($entities as $entity)
  {
    var_dump($entity);
    echo "<br/>";
  }
  echo "<br/>";
  
  /**
   * Delete the table "testtable"
   */
  $tableStorageClient->deletetable("testtable");
      
  /**
   * Wait for 35s as this operations takes time. One can use sleep(35) to put current thread in sleep state,
   * but default execution timeout for PHP is 30s. Hence increase timeout if you want to use sleep.
   */
  echo "Deleting table <b>'testtable'</b>. Wait for about <b>35s</b> to execute sample again.<br/><br/>";
} catch ( Microsoft_WindowsAzure_Exception $e ) {
  printf($e->getMessage());
} catch ( Exception $e ) {
  printf($e->getMessage());
  if( strpos($e->getMessage(), 'cURL error occured during request for http://127.0.0.1') !== false ){
		echo "<p style='color: red'>Please check the status of Development Storage. Make sure that it is running!<p/>";
  }
}

/**
 * Create Table Storage Client for table operations using account defined in ServiceConfiguration file
 *
 * @return Microsoft_WindowsAzure_Storage_Table New storageclient for Azure Storage Table
 */ 
function createTableStorageClient()
{
  if (isset($_SERVER['USERDOMAIN']) && $_SERVER['USERDOMAIN'] == 'CIS')
  {
    $host = Microsoft_WindowsAzure_Storage::URL_CLOUD_TABLE;
    $accountName = azure_getconfig('AzureCloudStorageAccountName');
    $accountKey = azure_getconfig('AzureCloudStorageAccountKey');
    $usePathStyleUri = true;
    
    $retryPolicy = Microsoft_WindowsAzure_RetryPolicy::retryN(10, 250);
    
    $tableStorageClient = new Microsoft_WindowsAzure_Storage_Table(
                              $host,
                              $accountName,
                              $accountKey,
                              $usePathStyleUri,
                              $retryPolicy
                              );
  }
  else
  {
    $tableStorageClient = new Microsoft_WindowsAzure_Storage_Table();
  }
        
	return $tableStorageClient;
}

/**
 * List all tables for specified account
 *
 * @param Microsoft_WindowsAzure_Storage_Table $tableStorageClient Table storage client 
 * @return None
 */
function listTables($tableStorageClient)
{
  echo "<b>List of Tables:<br/></b>";
  $result = $tableStorageClient->listTables();
  foreach ($result as $table)
  {
      echo "- $table->Name <br/>";
  }
    echo "<br/>";
}

/**
 * Determine if Windows Azure Table Service accessible.
 * 
 * @param object $tableStorageClient Windows Azure Table Service client.
 * 
 * @return boolean True if Table Service is accessible.
 */
function isTableServiceAccessible($tableStorageClient)
{
  $bSuccess = false;
  try
  {
    $tableStorageClient->listTables();
    $bSuccess = true;
  } 
  catch (Microsoft_WindowsAzure_Exception $ex) 
  {
    echo "<p style='color: red'>Windows Azure Table Service: Exception: \"{$ex->getMessage()}\"<p/>";
  }
  catch (Microsoft_Http_Transport_Exception $ex) 
  {
    $location = (isset($_SERVER['USERDOMAIN']) && $_SERVER['USERDOMAIN'] == 'CIS')
                ? "Azure Cloud" : "Development Fabric";
    if( strpos($ex->getMessage(), 'cURL error occured during request for http://127.0.0.1') !== false ){
      echo "<p style='color: red'>Please check Table Service status in {$location} Storage. Make sure that it is running!<p/>";
    }
  } 
  catch (Exception $ex)
  {
    echo "<p style='color: red'>Unexpected Windows Azure Table Service Exception: \"{$ex->getMessage()}\"<p/>";
  }
  
  return $bSuccess;
}
?>
 	  	 
