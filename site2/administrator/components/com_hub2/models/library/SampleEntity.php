<?php
/**
 * This sample will make use of the following entity class 
 * for storing data into Azure Table
 * 
 * Please refer http://phpazure.codeplex.com/wikipage?title=Defining%20entities%20for%20Table%20Storage&referringTitle=Table%20storage
 * for details about azure tables
 */
require_once 'Microsoft/WindowsAzure/Storage/Table.php';

class SampleEntity extends Microsoft_WindowsAzure_Storage_TableEntity
{
    /**
     * @azure Name
     */
    public $Name;
    
    /**
     * @azure Age Edm.Int64
     */
    public $Age;
    
    /**
     * @azure Visible Edm.Boolean
     */
    public $Visible = false;
}
?>