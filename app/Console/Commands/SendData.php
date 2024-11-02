<?php

namespace App\Console\Commands;

use Asif160627\ZktecoAccessControl\Facades\AccessControl;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SendData extends Command
{
    protected $signature = 'attendance:send';

    protected $description = 'Send attendance data every minutes';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            $startTime = Carbon::now()->subHours(48)->format('Y-m-d H:i:s');
            $endTime = Carbon::now()->addHours(6)->format('Y-m-d H:i:s');
            $data = AccessControl::getTransactions($page = 1, $page_size = 1000, $emp_code = null, $terminal_sn = null, $terminal_alias = null, $startTime, $endTime);
            $attendanceData = [];
            $token = "mah&*#(@())!!";

            foreach ($data['data'] as $entry) {
                $attendanceData[] = [
                    'id' => $entry['id'],
                    'emp_code' => $entry['emp_code'],
                    'punch_time' => $entry['punch_time'],
                ];
            }

            $client = new Client();
            $url = 'https://erp.mahmcm.com/api/punch/records/store';

            $response = $client->post($url, [
                'headers' => [
                    'X-API-TOKEN' => $token,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'data' => $attendanceData,
                ],
            ]);

            $responseBody = $response->getBody()->getContents();
            $this->info('Attendance data sent successfully.');
            $this->info($responseBody);

        } catch (Exception $e) {
            $this->error("Failed to send attendance data: " . $e->getMessage());
            Log::error('Attendance send command failed.', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
