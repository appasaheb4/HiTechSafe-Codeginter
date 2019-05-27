<?php

class MyProfile extends MY_Controller {

    //put your code here
    function __construct() {
        parent::__construct();
    }

    public function getUserInfomation() {
        header('Access-Control-Allow-Origin: *');
        $postdata12 = file_get_contents("php://input");
        $request14 = json_decode($postdata12);
        $id = $request14->id;
        $return_arr = array();
        $allresult = array();
        $this->db->where("CustID", $id);
        $query = $this->db->get('CustRegistration');
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $row_array['CustomerName'] = $row['CustomerName'];
                $row_array['CustomerAddress'] = $row['CustomerAddress'];
                $row_array['EmailID'] = $row['EmailID'];
                $row_array['UserName'] = $row['UserName'];
                $row_array['AdharNo'] = $row['AdharNo'];
                $row_array['LicenceNo'] = $row['LicenceNo'];
                $row_array['MobileNo'] = $row['MobileNo'];
                $row_array['coverImage'] = 'http://demo.winwaytechnology.in/' . $row['coverImage'];
                $row_array['imagePath'] = 'http://demo.winwaytechnology.in/' . $row['imagePath'];

                array_push($return_arr, $row_array);
            }
            $allresult['userAllInformation'] = $return_arr;
            echo json_encode($allresult);
        }
    }

    public function mobileUploadImage() {
        header('Access-Control-Allow-Origin: *');
        $options = file_get_contents("php://input");
        $request = json_decode($options);
        $userId = basename($_POST['userId']);
        $type = basename($_POST['type']);
        $target_path = "Content/Images/AppImages/MyProfile/";
        $target_path1 = $target_path . basename($_FILES['file']['name']);  
        $target_path2 = "ionicapp/Content/Images/AppImages/MyProfile/" . basename($_FILES['file']['name']); 
        if (move_uploaded_file($_FILES['file']['tmp_name'], $target_path1)) {  
            if($type=="cover")
            {   
                 $data = array(
                'coverImage' => $target_path2
            );   
            $this->db->where('CustID', $userId);
            $this->db->update('CustRegistration', $data);
            echo json_encode("yes");
                
            }else{
            $data = array(
                'imagePath' => $target_path2
            );   
            $this->db->where('CustID', $userId);
            $this->db->update('CustRegistration', $data);
            echo json_encode("yes");
            }
        }
       
    }
    
    public function updateDataMobile() {
        header('Access-Control-Allow-Origin: *');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $cusName = $request->cusName;
        $address = $request->address;
        $mobileNo = $request->mobileNo;
        $email = $request->email;  
        $userName = $request->userName;
        $adharNo = $request->adharNo;
        $linceNo = $request->linceNo;  
        $userId = $request->userId;
        if (!empty($cusName)) {
            $data = array(
                'CustomerName' => $cusName,
                'CustomerAddress' => $address,
                'EmailID' => $email,
                'UserName' => $userName,
                'AdharNo' => $adharNo,
                'LicenceNo' => $linceNo,
                'MobileNo' => $mobileNo
            );
            $this->db->where('CustID', $userId);
            $this->db->update('CustRegistration', $data);
            echo json_encode("yes");
        } else {  
            echo json_encode("no");    
        }
    }
    
    
    
    public function changePasswordMobile() {
        header('Access-Control-Allow-Origin: *');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $oldPass = $request->oldPass;
        $newPass = $request->newPass;
        $userId = $request->userId;
        $this->db->where('PassWord', $oldPass);
        $this->db->where('CustID', $userId);
        $query = $this->db->get('CustRegistration');
        if ($query->num_rows() > 0) {   
            foreach ($query->result_array() as $row) {
                $data = array(
                    'PassWord' => $newPass,
                   /// 'password_change_status' => 'true'
                );
                $this->db->where('CustID', $userId);
                $this->db->update('CustRegistration', $data);
                echo json_encode("yes");
            }   
        } else {
            echo json_encode("no");
        }
    }

}
