<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;


class DashboardController extends FunctionController
{

    public function __construct(){
        parent::__construct();
            if(session()->get('location') == "Kentwood"){
                $this->tableName = "inventory";
                $this->tableNamePre = "inventory";
            }
            else{
                $this->tableName = "inventory_houston";
            }
    }

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
//            'preCountAllTime' => $this->preCountAllTime(),
//            'yesterdayPreCounts' => $this->yesterdayPreCounts(),
//            'companyPreCounts' => $this->companyPreCounts()
        ];

        return json_encode($data);
    }

    // GET LAST WORKING DAY COUNTS OCCURRED FROM DATABASE
    public function getLastDay()
    {
        $todayCount = date('Y-m-d');
        $getLastDay = DB::select('
            SELECT date_counted
            FROM ' . $this->tableName . '
            WHERE date_counted = (
                SELECT MAX(date_counted)
                FROM ' . $this->tableName . ' AS yesterday
                WHERE date_counted < ?)
                ',
            [$todayCount]);

            return $getLastDay[0]->date_counted;
    }

    // GET LAST WORKING DAY PRE COUNTS OCCURRED FROM DATABASE
    public function getLastPreCountDay()
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
                ih.user,
                COUNT(ih.user) as counts,
                u.id,
                u.first_name as first_name,
                u.last_name as last_name
            FROM ' . $this->tableName . ' ih
            JOIN users u ON u.id = ih.user
            WHERE ih.date_counted = ?
            GROUP BY ih.user
            ORDER BY counts DESC;',
            [$yesterday]);

        return $yesterdayUserCounts;
    }

    //  GET ALL TIME USER COUNTS
    public function allTimeCounts(){
        $allTimeCounts = DB::select('
            SELECT
                ih.user,
                COUNT(user) as counts,
                u.id,
                u.first_name as first_name,
                u.last_name as last_name
            FROM ' . $this->tableName . ' ih
            JOIN users u ON u.id = ih.user
            WHERE ih.user != ?
            GROUP BY user
            ORDER BY counts DESC',
            ['']);

        return $allTimeCounts;
    }

    //  GET TOTAL COUNTS BY COMPANY
    public function countsByCompany(){
        $countsByCompany = DB::select('
            SELECT
                company,
                COUNT(count) as counts
            FROM ' . $this->tableName . '
            WHERE date_counted != ?
            GROUP BY company
            ORDER BY counts DESC',
            ['0000-00-00']);

        $countsByCompany = json_decode(json_encode($countsByCompany), true);
        for($i = 0; $i < count($countsByCompany); $i++){
            $countsByCompany[$i]['company'] = $this->epicorCodeToCompanyName($countsByCompany[$i]['company']);
        }

        return $countsByCompany;
    }

    //  GET PERCENTAGE OF INVENTORY COUNTED BY COMPANY
    public function percentageByCompany(){
        $percentageByCompany = DB::select('
            SELECT
                SUM(counted = ? )*100/count(*) AS percentage,
                company
            FROM ' . $this->tableName . '
            GROUP BY company',
            ['1']);

        $percentageByCompany = json_decode(json_encode($percentageByCompany), true);
        for($i = 0; $i < count($percentageByCompany); $i++){
            $percentageByCompany[$i]['company'] = $this->epicorCodeToCompanyName($percentageByCompany[$i]['company']);
        }

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
}
