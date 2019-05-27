<?php

class GridViewAll extends MY_Controller {

    //put your code here
    function __construct() {
        parent::__construct();
    }

    public function getGridViewAllData() {
        header('Access-Control-Allow-Origin: *');
        $postdataNew = file_get_contents("php://input");
        $request = json_decode($postdataNew);
        $userId = $request->userId;
        $return_arr = array();
        $allresult = array();
        $this->db->where("CustID", $userId);
        $query = $this->db->get('CustRegistration');
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $inUserId = $row['CustID'];
                $row_array['id'] = $inUserId;
                $row_array['VehicleName'] = $row['VehicleName'];
                $singleNo = $row['IMANo'];

                $this->db->where("IMEI", $singleNo);
                $this->db->order_by("id", "desc");
                $this->db->limit(1);
                $query1 = $this->db->get('Tbl_ASRMaster');
                if ($query1->num_rows() > 0) {
                    foreach ($query1->result_array() as $row1) {
                        $row_array['Digital_2_input_status'] = $row1['Digital_2_input_status'];
                        $row_array['Speed'] = $row1['Speed'];
                        $row_array['Main_Battery_Voltage'] = $row1['Main_Battery_Voltage'];
                        $row_array['Odometer'] = $row1['Odometer'];

                        $latitude = $row1['Latitude'];
                        $longitude = $row1['Longitude'];
                        $address = $this->getAddress($latitude, $longitude);
                        $address = $address ? $address : 'Not found';
                        $row_array['address']=$address;   
                    }
                }
                $row_array['VehicleType'] = $row['VehicleType'];
                $row_array['IMEI'] = $singleNo;
                $row_array['MobileNo'] = $row['MobileNo'];
                $row_array['Date'] = $row['Date'];
                array_push($return_arr, $row_array);
            }
        }
        $allresult['getAllViewData'] = $return_arr;
        echo json_encode($allresult);
    }

    public function getAddress($latitude, $longitude) {
        if (!empty($latitude) && !empty($longitude)) {
            $geocodeFromLatLong = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?latlng=' . trim($latitude) . ',' . trim($longitude) . '&sensor=false');
            //$geocodeFromLatLong = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?latlng=' . trim($latitude) . ',' . trim($longitude) . '&sensor=true_or_false&key=AIzaSyD7j4FT8LTdciXLyPwI0OAaRMFTXjl88ys');
            $output = json_decode($geocodeFromLatLong);
            $status = $output->status;
            //Get address from json data
            $address = ($status == "OK") ? $output->results[1]->formatted_address : '';
            //Return address of the given latitude and longitude
            if (!empty($address)) {
                return $address;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}
