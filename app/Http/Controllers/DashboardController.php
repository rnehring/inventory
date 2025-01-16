<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{


    public function index()
    {
        return view('dashboard',[
            'lastPreCountDay' => $this->getLastPreCountDay(),
            'yesterdayPreCounts' => $this->yesterdayPreCounts()
        ]);
    }


    // GET DASHBOARD DATA
    public function getDashboardData(){
        $data = [
            'allTimeCounts' => $this->allTimeCounts(),
            'yesterdayCounts' => $this->yesterdayCounts(),
            'percentageByCompany' => $this->percentageByCompany(),
            'preCountAllTime' => $this->preCountAllTime(),
            'yesterdayPreCounts' => $this->yesterdayPreCounts(),
            'companyPreCounts' => $this->companyPreCounts()
        ];

//        $data = json_decode(json_encode($data), true);
        return json_encode($data);
    }



    // GET LAST WORKING DAY COUNTS OCCURRED FROM DATABASE
    public static function getLastDay()
    {
        $todayCount = date('Y-m-d');
        $getLastDay = DB::select('
            SELECT date_counted
            FROM inventory
            WHERE date_counted = (
                SELECT MAX(date_counted)
                FROM inventory AS yesterday
                WHERE date_counted < ?)
                ',
            [$todayCount]);
        return $getLastDay[0]->date_counted;
    }



    // GET LAST WORKING DAY PRE COUNTS OCCURRED FROM DATABASE
    public static function getLastPreCountDay()
    {
        $today = date('Y-m-d');
        $getLastDay = DB::select('
            SELECT
                verified_date
            FROM pre_count
            WHERE verified_date = (
            SELECT MAX(verified_date)
            FROM pre_count AS yesterday
            WHERE verified_date < ?)',
            [$today]);
        return $getLastDay[0]->verified_date;
    }



    // GET USER COUNTS FROM LAST WORKING DAY
    public function yesterdayCounts(){
        $yesterday = $this->getLastDay();

        $yesterdayUserCounts = DB::select('
            SELECT
                user,
                COUNT(user) as counts
            FROM inventory
            WHERE date_counted = ?
            GROUP BY user ORDER BY counts DESC',
            [$yesterday]);

//        $yesterdayUserCounts = json_encode($yesterdayUserCounts);
        return $yesterdayUserCounts;
    }


    //  GET ALL TIME USER COUNTS
    public function allTimeCounts(){
        $allTimeCounts = DB::select('
            SELECT
                user,
                COUNT(user) as counts
            FROM inventory
            WHERE user != ?
            GROUP BY user
            ORDER BY counts DESC',
            ['']);

//        $allTimeCounts = json_encode($allTimeCounts);
        return $allTimeCounts;
    }


    //  GET TOTAL COUNTS BY COMPANY
    public function countsByCompany(){
        $countsByCompany = DB::select('
            SELECT
                company,
                COUNT(count) as counts
            FROM inventory
            WHERE date_counted != ?
            GROUP BY company
            ORDER BY counts DESC',
            ['0000-00-00']);

        $countsByCompany = json_decode(json_encode($countsByCompany), true);
        for($i = 0; $i < count($countsByCompany); $i++){
            $countsByCompany[$i]['company'] = $this->epicorCodeToCompanyName($countsByCompany[$i]['company']);
        }

//        $countsByCompany = json_encode($countsByCompany);
        return $countsByCompany;
    }



    //  GET PERCENTAGE OF INVENTORY COUNTED BY COMPANY
    public function percentageByCompany(){
        $percentageByCompany = DB::select('
            SELECT
                SUM(date_counted != ? )*100/count(*) AS percentage,
                company
            FROM inventory
            GROUP BY company',
            ['0000-00-00']);

        $percentageByCompany = json_decode(json_encode($percentageByCompany), true);
        for($i = 0; $i < count($percentageByCompany); $i++){
            $percentageByCompany[$i]['company'] = $this->epicorCodeToCompanyName($percentageByCompany[$i]['company']);
        }

//        $percentageByCompany = json_encode($percentageByCompany);
        return $percentageByCompany;
    }


    // ALL TIME PRECOUNTS
    public function preCountAllTime(){
        $preCountAllTime = DB::select('
            SELECT
                user,
                COUNT(user) as counts
            FROM pre_count
            WHERE user != ?
            GROUP BY user
            ORDER BY counts DESC',
            ['']);

//        $preCountAllTime = json_encode($preCountAllTime);
        return $preCountAllTime;
    }


    //  PRE COUNTS YESTERDAY
    public function yesterdayPreCounts(){
        $yesterday = $this->getLastPreCountDay();
        $yesterday = explode(' ', $yesterday);
        $yesterday = $yesterday[0];

        $yesterdayStart = $yesterday . " 00:00:00";
        $yesterdayEnd = $yesterday . " 23:59:59";

        $yesterdayUserPreCounts = DB::select('
            SELECT
                user,
                COUNT(user) as counts
            FROM pre_count
            WHERE verified_date >  ? AND verified_date < ?
            GROUP BY user
            ORDER BY counts DESC
            ',
            [$yesterdayStart, $yesterdayEnd]);

//        $yesterdayUserPreCounts = json_encode($yesterdayUserPreCounts);
        return $yesterdayUserPreCounts;
    }


    // PRE COUNTS BY COMPANY
    public function companyPreCounts(){
        $companyPreCounts = DB::select('
            SELECT
                company,
                COUNT(bin_verified) as counts
            FROM pre_count
            WHERE bin_verified = ?
            GROUP BY company ORDER BY counts DESC',
            ['1']);

        return $companyPreCounts;
    }


    //  HELPER CLASS TO CONVERT EPICOR COMPANY CODES TO TEXT READABLE COMPANY NAMES
    public static function epicorCodeToCompanyName($code){
        switch($code){
            case "00":
                return "Andronaco Industries";
            case "10":
                return "PureFlex";
            case "20":
                return "Nil-Cor";
            case "30":
                return "Ethylene";
            case "40":
                return "Hills-McCanna";
            case "50":
                return "Ramparts Pumps";
            case "CC0":
                return "Conley Composites";
            case "FC0":
                return "FlowCor";
            case "G50":
                return "Endurance Composites";
            case "GWS":
                return "Great Western Supply";
            case "PV0":
                return "PolyValve";
        }
    }




}
