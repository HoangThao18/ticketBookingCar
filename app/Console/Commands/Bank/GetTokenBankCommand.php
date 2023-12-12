<?php

namespace App\Console\Commands\Bank;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class GetTokenBank extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bank:get-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Login bank';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->refreshTokenBank();
    }

    
    function refreshTokenBank()
    {
        $client = new Client();
        $headers = [
            'Accept' => ' application/json, text/plain',
            'Accept-Encoding' => ' gzip, deflate, br',
            'Accept-Language' => ' vi,en-GB;q=0.9,en-US;q=0.8,en;q=0.7,ja;q=0.6,en-GB-oxendict;q=0.5',
            'Connection' => ' keep-alive',
            'Content-Length' => ' 179',
            'Content-Type' => ' application/json; charset=UTF-8',
            'Host' => ' app2.timo.vn',
            'Origin' => ' https://my.timo.vn',
            'Referer' => ' https://my.timo.vn/',
            'Sec-Fetch-Dest' => ' empty',
            'Sec-Fetch-Mode' => ' cors',
            'Sec-Fetch-Site' => ' same-site',
            'User-Agent' => ' Mozilla/5.0 (Linux; Android 13; SM-G981B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Mobile Safari/537.36',
            'sec-ch-ua' => ' "Google Chrome";v="119", "Chromium";v="119", "Not?A_Brand";v="24"',
            'sec-ch-ua-mobile' => ' ?1',
            'sec-ch-ua-platform' => ' "Android"',
            'x-gofs-context-id' => ' a4486d1cdc96078ddc9840c4a8f24363e6d18c582ea4a2fbb4bd88b69c9db2b1.31e94cb8-833e-416a-950d-d606a58b2865',
            'x-timo-devicereg' => ' 0f1e58fab6811a5330e9a485877ba55b:WEB:WEB:194:WEB:mobile:chrome'
        ];
        $body = '{"username":"0377457747","password":"67cfa1a1ea0c40d959d777481d2630fdceaae112b3ddb063006e467d5c215fde1cd3b13bfa89b0d4ff790f2e4c3aca2f668c8d1ae1d76badbf8dae7b339531dd","lang":"EN"}';
        $res = $client->request('POST', 'https://app2.timo.vn/login', [
            'headers' => $headers,
            'body' => $body,
        ]);
        $data = json_decode($res->getBody());
        $token = $data->data->token;

        // Đường dẫn đến file services.php
        $filePath = config_path('services.php');

        // Đọc nội dung của file services.php
        $content = File::get($filePath);

        // Chỉnh sửa cấu hình theo
        $old_token = config('services.bank.token');
        $content = str_replace("'token' => '$old_token'", "'token' => '$token'", $content);

        // Lưu nội dung đã sửa lại vào file
        File::put($filePath, $content);
        $this->info("Get token successfully");
    }

    
}
