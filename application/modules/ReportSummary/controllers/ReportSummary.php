<?php

class ReportSummary extends MY_Controller {

//put your code here
    function __construct() {
        parent::__construct();
    }

    public function getAllVehicleName() {
        header('Access-Control-Allow-Origin: *');
        $postdataNew = file_get_contents("php://input");
        $request = json_decode($postdataNew);
        $userId = $request->userId;
        $return_arr = array();
        $allresult = array();
        $this->db->where("CustID", $userId);
        //$this->db->distinct();   
        //$this->db->select('VehicleName','IMANo','CustID');
        $query = $this->db->get('CustRegistration');  //$this->db->query("SELECT DISTINCT VehicleName FROM CustRegistration");//
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $row_array['CustID'] = $row['CustID'];
                $row_array['IMANo'] = $row['IMANo'];
                $row_array['VehicleName'] = $row['VehicleName'];
                array_push($return_arr, $row_array);
            }
        }
        $allresult['allVehicleName'] = $return_arr;
        echo json_encode($allresult);
    }

    public function getDemoData() {
        header('Access-Control-Allow-Origin: *');
        $postdataNew = file_get_contents("php://input");
        $request = json_decode($postdataNew);
        $return_arr = array();
        $allresult = array();
        $vehicleName = $request->vehicleName;
        $sp = $request->startDate;
        $split22 = $request->endDate;
        $startDate = date("dmy", strtotime($sp));
        $endDate = date("dmy", strtotime($split22));
        $VehicleName00 = "";
        $startDate00 = "";
        $startTime00 = "";
        $address00 = "";
        $VehicleName22 = "";
        $startDate22 = "";
        $startTime22 = "";
        $address22 = "";
        $dis = "";
        $endDateTime = "";
        $startDateTime = "";  //use
        $newDate = "";
        $contents='';
        $newDate1 = "";
        $lat11 = "";
        $laung11 = "";
        $lat22 = "";
        $laung22 = "";
        $cs = 0;
        $addcont = 0;
        $currentTimeHour = "";
        $currentTimeMin = "";
        $currentTimeSec = "";
        $currentTimeHourOut = "";
        $currentTimeMinOut = "";
        $currentTimeSecOut = "";
        $minDate00 = "";
        $minDate11 = "";
        $this->db->where("IMEI", $vehicleName);
        $this->db->where('GPS_Date >=', $startDate);
        $this->db->where('GPS_Date <=', $endDate);
        $query = $this->db->get('Tbl_ASRMaster');
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                
                $ddd = $row['Digital_2_input_status'];
                if ($ddd == "0") {
                    if ($cs == 0) {
                        try {
                            $imeiNo = $row['IMEI'];
                            $this->db->where('IMANo', $imeiNo);
                            $query1 = $this->db->get('CustRegistration');
                            if ($query1->num_rows() > 0) {
                                foreach ($query1->result_array() as $row1) {
                                    $VehicleName00 = $row1['VehicleName'];
                                }
                            } else {
                                $VehicleName00 = '';
                            }
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
                            $endDateTime = $currentFullDateIn . '  ' . date('h:i:s a', strtotime($currentTimeFull));
                            $minDate11 = $currentTimeHour . ':' . $currentTimeMin . ':' . $currentTimeSec;
                            $lat11 = $row['Latitude'];
                            $laung11 = $row['Longitude'];
                            if ($addcont == 1) {
                               // $contents = $this->getAddress($row['Latitude'], $row['Longitude']);                    //file_get_contents('http://myapparelhub.com/getMapAddress.php?lat='.$lat11.'&lng='.$laung11.'');
                                $address00 =  'Ahmdnagar';
                                $row_array['VehicleName'] = $VehicleName00;
                                $row_array['startDate'] = $startDateTime;
                                $row_array['startAddress'] = $address00;
                                $row_array['endTime'] = $endDateTime;
                                $row_array['endAddress'] = '';
                                $row_array['Total_Running_Hours'] = 'Run';
                                $seconds = strtotime($minDate11) - strtotime($minDate00);
                                $hours = floor($seconds / 3600);
                                $mins = floor(($seconds - ($hours * 3600)) / 60);
                                $secs = floor($seconds % 60);
                                if ($seconds < 60)
                                    $time = $secs . " seconds ago";
                                else if ($seconds < 60 * 60)
                                    $time = $mins . " min ago";
                                else if ($seconds < 24 * 60 * 60)
                                    $time = $hours . " hours ago";
                                $row_array['Total_Idle_Hours'] = $time;
                                array_push($return_arr, $row_array);
                                $addcont = 0;
                            }
                        } catch (Exception $e) {
                            return $e;
                        }
                        $cs = 1;
                    } else {
                        if ($cs == 1) {
                            try {
                                $imeiNo = $row['IMEI'];
                                $this->db->where('IMANo', $imeiNo);
                                $query1 = $this->db->get('CustRegistration');
                                if ($query1->num_rows() > 0) {
                                    foreach ($query1->result_array() as $row1) {
                                        $VehicleName22 = $row1['VehicleName'];
                                    }
                                } else {
                                    $VehicleName22 = '';
                                }
                                $currentInFullDateOut = $row['GPS_Date'];
                                $currentDateInOut = substr($currentInFullDateOut, 0, 2);
                                $currentMonthInOut = substr($currentInFullDateOut, 2, 2);
                                $currentYearInOut = substr($currentInFullDateOut, 4, 6);
                                $currentInFullTimeOut = $row['GPS_TIME'];
                                $currentTimeHourOut = substr($currentInFullTimeOut, 0, 2);
                                $currentTimeMinOut = substr($currentInFullTimeOut, 2, 2);
                                $currentTimeSecOut = substr($currentInFullTimeOut, 4, 6);
                                $currentFullDateInOut = $currentDateInOut . '-' . $currentMonthInOut . '-' . $currentYearInOut;
                                $currentTimeFullOut = $currentTimeHourOut . ':' . $currentTimeMinOut . ':' . $currentTimeSecOut;
                                $startDateTime = $currentFullDateInOut . '  ' . date('h:i:s a', strtotime($currentTimeFullOut));
                                $minDate00 = $currentTimeHourOut . ':' . $currentTimeMinOut . ':' . $currentTimeSecOut;
                                $lat22 = $row["Latitude"];  
                                $laung22 = $row["Longitude"];
                                if ($addcont == 0) {      
                                   // $contents = $this->getAddress($row['Latitude'], $row['Longitude']);       // file_get_contents('http://myapparelhub.com/getMapAddress.php?lat='.$lat22.'&lng='.$laung22.'');
                                    $address22 =  'Ahmdnagar';        
                                    $row_array['VehicleName'] = $VehicleName22;
                                    $row_array['startDate'] = $endDateTime;
                                    $row_array['startAddress'] = '';
                                    $row_array['endTime'] = $startDateTime;
                                    $row_array['endAddress'] = $address22;
                                    $row_array['Total_Idle_Hours'] = 'Stop';
                                    $seconds = strtotime($minDate00) - strtotime($minDate11);
                                    $hours = floor($seconds / 3600);
                                    $mins = floor(($seconds - ($hours * 3600)) / 60);
                                    $secs = floor($seconds % 60);
                                    if ($seconds < 60)
                                        $time = $secs . " seconds ago";
                                    else if ($seconds < 60 * 60)
                                        $time = $mins . " min ago";
                                    else if ($seconds < 24 * 60 * 60)
                                        $time = $hours . " hours ago";
                                    $row_array['Total_Running_Hours'] = $time;
                                    array_push($return_arr, $row_array);
                                    $addcont = 1;
                                }
                            } catch (Exception $ex) {
                                return $ex;
                            }
                        }
                        $cs = 0;
                    }
                }
            }
        }
        $allresult['getSummaryAllData'] = $return_arr;
        echo json_encode($allresult);
    }
    
    
     public function getAddress($latitude, $longitude) {
        if (!empty($latitude) && !empty($longitude)) {
            $geocodeFromLatLong = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?latlng=' . trim($latitude) . ',' . trim($longitude) . '&sensor=false');
            $output = json_decode($geocodeFromLatLong);
            $status = $output->status;
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
