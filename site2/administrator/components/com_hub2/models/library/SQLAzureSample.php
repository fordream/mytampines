<?php
/**
 * Please confiure your connection string for SQL Azure by right clicking on web role project, 
 * click properties and select Windows Azure->SQL Azure in the treeview. Specify all SQL Azure 
 * connections settings in this dialog (hostname, database name, user name and password.
 * 
 */
 
 /*
  * To run this SQL Azure application, user need to estabilish a SQL Azure account.
  * 
  */
$host   = "AccountName.database.windows.net";
$dbname = "master";
$dbuser = "UserID@AccountName";
$dbpwd  = "UserID_Password";
$driver = "{SQL Server Native Client 10.0}";

/**
 * Make SQL Azure database connection to the master table
 */
$masterDB = "master";
$conn = sqlsrv_connect($host, array("Database" => $masterDB, 
									"UID" => $dbuser, 
									"PWD" => $dbpwd, 
									"MultipleActiveResultSets" => '0'))
  or die("Couldn't connect to SQL Server on $host");

/**
 * Perform cleanup silently
 */ 
$query = "DROP DATABASE TestDB;";
$result = sqlsrv_query($conn, $query);
  
/**
 * Display list of databases
 */ 
echo "<h3>List of Databases in SQL Azure</h3>";
$query = "SELECT name FROM sys.databases;";
$result = sqlsrv_query($conn, $query);
if ($result === false)
{
    print_r(sqlsrv_errors(SQLSRV_ERR_ERRORS));
    die("Couldn't fetch list of databases");
}
else
{
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
    {
        echo " - " . $row["name"] . "<br/>";
    }
}

/**
 * Create new database TestDB
 */ 
$query = "CREATE DATABASE TestDB;";
$result = sqlsrv_query($conn, $query);
if ($result === false)
{
    print_r(sqlsrv_errors(SQLSRV_ERR_ERRORS));
    die("Couldn't create database 'TestDB");
}
else
{
    echo "<h3>Created Database 'TestDB' in SQL Azure</h3>";
}

/**
 * Again Display list of databases
 */
echo "<h3>Now List of Databases in SQL Azure</h3>";
$query = "SELECT name FROM sys.databases;";
$result = sqlsrv_query($conn, $query);
while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
{
    echo " - " . $row["name"] . "<br/>";
}

sqlsrv_close($conn);
echo "<h3>Closed connection to the 'master' database in SQL Azure</h3>";

/**
 * Prepare connection on TestDB
 */
$connTestDB = sqlsrv_connect($host, array("Database" => "TestDB", 
											"UID" => $dbuser, 
											"PWD" => $dbpwd, 
											"MultipleActiveResultSets" => '0'))
  or die("Couldn't make connection to 'TestDB' database to SQL Server on $host");
echo "<h3>Prepared connection to the 'TestDB' database in SQL Azure</h3>";
  
/**
 * Create table in SQL Azure
 */
$query = "CREATE TABLE Account (AccountID int PRIMARY KEY CLUSTERED, Name varchar(MAX), Balance int);";
$result = sqlsrv_query($connTestDB, $query);
if ($result === false)
{
    print_r(sqlsrv_errors(SQLSRV_ERR_ERRORS));
    die("Couldn't create table 'Account' in 'TestDB' database");
}
else
{
    echo "<h3>Created 'Account' table in 'TestDB' database in SQL Azure</h3>";
}

/**
 * Insert data into Account Table
 */
$query = "INSERT INTO Account (AccountID, Name, Balance) VALUES (?, ?, ?);"; 

/* Prepare data to be inserted */
$row1 = array(1001, 'John', 10000);
$row2 = array(1002, 'Smith', 20000);
$row3 = array(1003, 'David', 30000);

/* Execute insert queries */
$result = sqlsrv_query($connTestDB, $query, $row1);
$result = sqlsrv_query($connTestDB, $query, $row2);
$result = sqlsrv_query($connTestDB, $query, $row3);
echo "<h3>Inserted 3 rows into the 'Account' table of 'TestDB' database in SQL Azure</h3>";

/**
 * Fetch data from Account Table
 */ 
$query = "SELECT * FROM Account;";
$result = sqlsrv_query($connTestDB, $query);
if ($result)
{
    echo "<h3>'Account' table contains following rows</h3>";
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
    {
        echo " - AccountID = " . $row['AccountID'] . ", Name = " .  $row['Name'] . ", Balance = " . $row['Balance'] . "<br/>";
    }
} 
else 
{
     echo "Error in statement execution.\n";
     die( print_r( sqlsrv_errors(), true));
}

/**
 * Update John's Balance to 50000
 */
$query = "UPDATE Account SET Balance = 50000 WHERE AccountID = 1001;";
$result = sqlsrv_query($connTestDB, $query);
if ($result)
{
    echo "<h3>Updated John's Balance to 50000</h3>";
} 
else 
{
     echo "Error in statement execution.\n";
     die( print_r( sqlsrv_errors(), true));
}

/**
 * AGain fetch data from Account Table
 */ 
$query = "SELECT * FROM Account;";
$result = sqlsrv_query($connTestDB, $query);
if ($result)
{
    echo "<h3>Now 'Account' table contains following rows</h3>";
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
    {
        echo " - AccountID = " . $row['AccountID'] . ", Name = " .  $row['Name'] . ", Balance = " . $row['Balance'] . "<br/>";
    }
} 
else 
{
     echo "Error in statement execution.\n";
     die( print_r( sqlsrv_errors(), true));
}

/**
 * Drop Account Table
 */ 
$query = "DROP TABLE Account;";
$result = sqlsrv_query($connTestDB, $query);
if ($result === false)
{
    print_r(sqlsrv_errors(SQLSRV_ERR_ERRORS));
    die("Couldn't drop 'Account' table in database 'TestDB'");
}
else
{
    echo "<h3>Dropped 'Account' table in database 'TestDB'</h3>";
}

sqlsrv_close($connTestDB);
echo "<h3>Closed connection to the 'TestDB' database in SQL Azure</h3>";

/**
 * Drop database TestDB. Need to connect to master database
 */ 
$conn = sqlsrv_connect($host, array("Database" => $masterDB, 
											"UID" => $dbuser, 
											"PWD" => $dbpwd, 
											"MultipleActiveResultSets" => '0'))
  or die("Couldn't connect to SQL Server on $host");
echo "<h3>Drop database 'TestDB' in SQL Azure</h3>";
$query = "DROP DATABASE TestDB;";
$result = sqlsrv_query($conn, $query);
if ($result === false)
{
    print_r(sqlsrv_errors(SQLSRV_ERR_ERRORS));
    die("Couldn't drop database 'TestDB");
}
else
{
    echo "<p>Database 'TestDB' dropped successfully.</p>";
}

/**
 * Finally display list of databases
 */
echo "<h3>Now List of Databases in SQL Azure</h3>";
$query = "SELECT name FROM sys.databases;";
$result = sqlsrv_query($conn, $query);
if ($result === false)
{
    print_r(sqlsrv_errors(SQLSRV_ERR_ERRORS));
    die("Couldn't fetch list of databases");
}
else
{
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
    {
        echo " - " . $row["name"] . "<br/>";
    }
}

/**
 * Cleanup database resources
 */ 
sqlsrv_free_stmt($result);
sqlsrv_close($conn);
?>
