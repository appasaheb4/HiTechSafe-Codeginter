<?php

class Login extends MY_Controller {

    //put your code here

    function __construct() {
        parent::__construct();
    }
    
    
    public function loginMobile() {
        header('Access-Control-Allow-Origin: *');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $userName = $request->userName;
        $password = $request->password;      
        $tokenNoNew = $request->tokenNo;     
        $this->db->where('ActiveOrBlock', '   Active');
        $this->db->where('UserName', $userName);
        $this->db->where('PassWord', $password);
        $query = $this->db->get('CustRegistration');   
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $newId = $row['CustID'];
                $alldata = "yes" . "=" . $newId;  
                echo json_encode($alldata);
                break;    
            }
            
        } else {
            echo json_encode('no');
        }
    }
    
    public function loginMobileOtherMethod() {
       // require_once('DBConnection.php');   
        header('Access-Control-Allow-Origin: *');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $userName = $request->userName;
        $password = $request->password;      
        $tokenNoNew = $request->tokenNo; 
        $serverName = "43.255.152.26";
        $uid = "as";
        $pwd = "asrsys@3003";
        $databaseName = "ASRSolutions";
         $connectionInfo = array("UID" => $uid,
            "PWD" => $pwd,
            "Database" => $databaseName);
        /* Connect using SQL Server Authentication. */
        $conn = sqlsrv_connect($serverName, $connectionInfo);
        $tsql = "select *  from CustRegistration where UserName='$userName' and PassWord='$password' and  ActiveOrBlock='   Active'";
        /* Execute the query. */
        $stmt = sqlsrv_query($conn, $tsql);
        if ($stmt) {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC)) {     
            $newId =  $row[0];
                $alldata = "yes" . "=" . $newId;
                echo json_encode($alldata);
        }
        } else {   
             echo json_encode("no");
        }
        /* Iterate through the result set printing a row of data upon each iteration. */
       
        /* Free statement and connection resources. */
        sqlsrv_free_stmt($stmt);
        sqlsrv_close($conn);
    }
    
     public function forgotPasswordMobile() {
        header('Access-Control-Allow-Origin: *');
        $data = file_get_contents("php://input");
        $request = json_decode($data);
        $email = $request->email;
         $serverName = "43.255.152.26";
        $uid = "as";
        $pwd = "asrsys@3003";
        $databaseName = "ASRSolutions";
         $connectionInfo = array("UID" => $uid,
            "PWD" => $pwd,
            "Database" => $databaseName);
        /* Connect using SQL Server Authentication. */
        $conn = sqlsrv_connect($serverName, $connectionInfo);
        $tsql = "select  PassWord from CustRegistration where EmailID='$email'";
        /* Execute the query. */
        $stmt = sqlsrv_query($conn, $tsql);
        if ($stmt) {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC)) {    
                $password = 'Your password is = ' . $row[0];
                $password = wordwrap($password, 70);  
                mail($email, "Forgot Password", $password);
                 echo json_encode("yes");
        }
        } else {   
             echo json_encode("no");
        }
    }
    
    

}
