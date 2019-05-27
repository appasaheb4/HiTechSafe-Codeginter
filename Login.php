<?php 
$request=file_get_contents('php://input');
$input=json_decode($request,true);
$name =$input['userName'];
$pass = $input['password'];
$serverName = "43.255.152.26";   
$uid = "as";        
$pwd = "asrsys@3003";    
$databaseName = "ASRSolutions";   
$connectionInfo = array( "UID"=>$uid,                              
                         "PWD"=>$pwd,                              
                         "Database"=>$databaseName);   
/* Connect using SQL Server Authentication. */    
$conn = sqlsrv_connect( $serverName, $connectionInfo);    
$tsql = "SELECT * FROM CustRegistration WHERE UserName='$name' AND PassWord='$pass' AND ActiveOrBlock='Active'";       
/* Execute the query. */    
$stmt = sqlsrv_query( $conn, $tsql);       
if ( $stmt )    
{    
    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC))    
    {    
        echo "Col1: ".$row[0]."\n";    
        echo "Col2: ".$row[1]."\n";    
        echo "Col3: ".$row[2]."<br>\n";    
        echo "-----------------<br>\n";    
    }   
}     
else     
{    
    echo "Error in statement execution.\n";    
    die( print_r( sqlsrv_errors(), true));    
}    
/* Iterate through the result set printing a row of data upon each iteration.*/    
    
/* Free statement and connection resources. */    
sqlsrv_free_stmt( $stmt);    
sqlsrv_close( $conn); 
?>

