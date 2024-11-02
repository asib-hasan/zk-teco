<?php

namespace App\Http\Controllers;

use Asif160627\ZktecoAccessControl\Facades\AccessControl;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DeviceController extends Controller
{
    public function index(){
        $startTime = Carbon::now()->subHours(48)->format('Y-m-d H:i:s');
        $endTime = Carbon::now()->addHours(6)->format('Y-m-d H:i:s');
        return $data = AccessControl::getTransactions($page = 1, $page_size = 1000, $emp_code = null, $terminal_sn = null, $terminal_alias = null, $startTime, $endTime);
        $attendanceData = [];
        $token = "mah&*#(@())!!";
        foreach ($data['data'] as $entry) {
            $attendanceData[] = [
                'id' => $entry['id'],
                'emp_code' => $entry['emp_code'],
                'punch_time' => $entry['punch_time'],
            ];
        }

        $data = $attendanceData;

        $client = new Client();

        // Define the request URL
        $url = 'https://erp.mahmcm.com/api/punch/records/store';

        $response = $client->post($url, [
            'headers' => [
                'X-API-TOKEN' => $token,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'data' => $data,
            ],
        ]);

        $responseBody = $response->getBody()->getContents();

        return response()->json(['response' => $responseBody]);
    }
}
