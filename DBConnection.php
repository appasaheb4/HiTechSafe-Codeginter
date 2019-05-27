<?php    
$serverName = "43.255.152.26";   
$uid = "as";        
$pwd = "asrsys@3003";    
$databaseName = "ASRSolutions";   
$connectionInfo = array( "UID"=>$uid,                              
                         "PWD"=>$pwd,                              
                         "Database"=>$databaseName);   
/* Connect using SQL Server Authentication. */    
$conn = sqlsrv_connect( $serverName, $connectionInfo);      
sqlsrv_free_stmt( $stmt);    
sqlsrv_close( $conn);    
?>  