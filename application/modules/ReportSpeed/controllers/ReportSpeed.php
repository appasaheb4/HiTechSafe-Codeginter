<?php

class ReportSpeed extends MY_Controller {

    //put your code here
    function __construct() {
        parent::__construct();
    }

    public function getrepootSpeedDataMobile() {
        header('Access-Control-Allow-Origin: *');
        $postdataNew = file_get_contents("php://input");
        $request = json_decode($postdataNew);
        $vehicleName = $request->vehicleName;
        $inFormDate = $request->startDate;
        $outToDate = $request->endDate;
        $speed = $request->speed;
        $contents = '';


        $thisSpeed = '';
        $finalSpeed = strlen($speed);
        if ($finalSpeed == 3) {
            $thisSpeed = $speed;
        } else if ($finalSpeed == 2) {
            $thisSpeed = sprintf("%03d", $speed);
        } else {
            $thisSpeed = sprintf("%03d", $speed);
        }


        $return_arr = array();
        $allresult = array();
        $startDate = date("dmy", strtotime($inFormDate));
        $endDate = date("dmy", strtotime($outToDate));
        $this->db->where("IMEI", $vehicleName);
        $this->db->where('GPS_Date >=', $startDate);
        $this->db->where('GPS_Date <=', $endDate);
        $this->db->where('Speed >=', $thisSpeed);
        $query = $this->db->get('Tbl_ASRMaster');
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $imeiNo = $row['IMEI'];
                $this->db->where('IMANo', $imeiNo);
                $query1 = $this->db->get('CustRegistration');
                if ($query1->num_rows() > 0) {
                    foreach ($query1->result_array() as $row1) {
                        $row_array['VehicleName'] = $row1['VehicleName'];
                    }
                } else {
                    $row_array['VehicleName'] = '';
                }
                $row_array['Speed'] = $row['Speed'];
                $currentInFullDate = $row['GPS_Date'];
                $currentDateIn = substr($currentInFullDate, 0, 2);
                $currentMonthIn = substr($currentInFullDate, 2, 2);
                $currentYearIn = substr($currentInFullDate, 4, 6);
                $currentInFullTime = $row['GPS_TIME'];
                $currentTimeHour = substr($currentInFullTime, 0, 2);
                $currentTimeMin = substr($currentInFullTime, 2, 2);
                $currentTimeSec = substr($currentInFullTime, 4, 6);
                $currentTimeFull = $currentTimeHour . ':' . $currentTimeMin . ':' . $currentTimeSec;
                $currentFullDateIn = $currentDateIn . '-' . $currentMonthIn . '-' . $currentYearIn;
                $row_array['DateTime'] = $currentFullDateIn . '  ' . date('h:i:s a', strtotime($currentTimeFull));


//                $contents = file_get_contents('http://myapparelhub.com/getMapAddress.php?lat=' . $row['Latitude'] . '&lng=' . $row['Longitude'] . '');
//                if (empty($contents)) {
//                    $contents = $this->getAddress($row['Latitude'], $row['Longitude']); //file_get_contents('http://myapparelhub.com/getMapAddress.php?lat=' . $row['Latitude'] . '&lng=' . $row['Longitude'] . '');
//                } else {
//                   $contents=$contents; 
//                }
                $row_array['Address'] = 'Ahmdnagar'; // $contents;  
                $row_array['lat'] = $row['Latitude'];
                $row_array['lag'] = $row['Longitude'];
                array_push($return_arr, $row_array);
            }
        }
        $allresult['getReportSpeed'] = $return_arr;
        echo json_encode($allresult);
    }

    public function getAddress($latitude, $longitude) {
        if (!empty($latitude) && !empty($longitude)) {
            $geocodeFromLatLong = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?latlng=' . trim($latitude) . ',' . trim($longitude) . '&sensor=false');
            $output = json_decode($geocodeFromLatLong);
            $status = $output->status;
            //Get address from json data
            $address = ($status == "OK") ? $output->results[1]->formatted_address : '';
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
