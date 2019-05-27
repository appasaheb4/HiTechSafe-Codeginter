<?php

class MapViewAll extends MY_Controller {

    //put your code here
    function __construct() {
        parent::__construct();
    }

    public function getLagAndLat() {
        header('Access-Control-Allow-Origin: *');
        $postdataNew = file_get_contents("php://input");
        $request = json_decode($postdataNew);
        $userId = $request->userId;
        $return_arr = array();
        $allresult = array();
        $this->db->where("CustID", $userId);
        $query = $this->db->get('CustRegistration');
        $lat = '';
        $lag = '';
        $fullAddress = '';
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $inUserId = $row['CustID'];
                $singleNo = $row['IMANo'];
                $this->db->where("IMEI", $singleNo);
                $this->db->order_by("id", "desc");
                $this->db->limit(1);
                $query1 = $this->db->get('Tbl_ASRMaster');
                if ($query1->num_rows() > 0) {
                    foreach ($query1->result_array() as $row1) {
                        $lat = $row1['Latitude'];
                        $lag = $row1['Longitude'];
                    }
                }
                $row_array['lat'] = $lat;
                $row_array['lag'] = $lag;
                
                
                
                $url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=" . $lat . "," . $lag . "&sensor=false";
                $json = @file_get_contents($url);
                $data = json_decode($json);
                $status = $data->status;
                if ($status == "OK") {
                    $fullAddress = $data->results[0]->formatted_address;
                } else {
                    $fullAddress = "No Data Found Try Again";
                }
                $row_array['address'] = $fullAddress;
                array_push($return_arr, $row_array);
            }
            $allresult['getlagandlat'] = $return_arr;
            echo json_encode($allresult);
        } else {
            echo json_encode("no");
        }
    }

    public function getPageLocation() {
        $lat =19.18666; //26.754347; //latitude      
        $lng =074.67665; //81.001640; //longitude 
        $contents = file_get_contents('http://myapparelhub.com/getMapAddress.php?lat='.$lat.'&lng='.$lng.'');
        if ($contents) {
            echo $contents;
        } else {
           // $lat = 26.754347; //latitude   
            //$lng = 81.001640; //longitude
            //$address = getaddress($lat, $lng);
            echo "Not found";
        }
    }

}
