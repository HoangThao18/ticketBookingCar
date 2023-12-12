<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\File;

class GetListMoMo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get-list:momo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get list momo';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $client = new Client();
        $headers = [
            'Host' => ' m.mservice.io',
            'userId' => ' 0886543301',
            'app_code' => ' 4.1.8',
            'user_phone' => ' 0886543301',
            'User-Agent' => ' MoMoPlatform Store/4.1.8.41081 CFNetwork/1402.0.8 Darwin/22.2.0 (iPhone 11 iOS/16.2) AgentID/38619274',
            'vsign' => ' AVAM3K/5rC5gVxFqrwAwmxNgE1AcFKcGWHUGOsUD1A1Rsfimg9SCD6qQHLu0ysygKmUHtr9vW3QvN1aE0mNxUfzbTRuIY8fJdZmtrSumq0KX7AyvwvZTi3ZY21jgg3YdpVY2DIgkQQyYQcLdiH8aZG4JwoVn3f9ZR3Xr2YxJlH3rw4hu8UwX9FOTyn6CMWSPHm8EDq3Cwpd4duJIaf+r6R1p/MP/EM8WpVC73ipVq9kHgAnJfml7kXjz2mE3gU3SYRH/KSlBjnnsi4778pTikwdqwHdMw6Re+g5uJh0WF5j/8Fxzp2Bco1BxFxyjEtxzbc5zvqxE9jE9YIrq3+v58g==',
            'lang' => ' vi',
            'app_version' => ' 41081',
            'tbid' => ' 58E919F1F864E5A1462DC86FE24075584678DE9D',
            'sessionKey' => ' e01c9308-6531-4f82-b703-dc1d8fa7c7a6',
            'channel' => ' APP',
            'momo-session-key-tracking' => ' C4DB7E85-8EB4-413C-BB1E-6B569F6DFD41',
            'vversion' => ' 1004',
            'Connection' => ' keep-alive',
            'Authorization' => ' Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJ1c2VyIjoiMDg4NjU0MzMwMSIsImltZWkiOiIxYTcwNWM5ODRhNjczMzI2YjUzYzliYzNiNmFlYmQ1NzY1OWFiYmFkYWVjMjFlMTc3ZjZjYTZiY2MzNWNhYmJiIiwiQkFOS19DT0RFIjoiMTEwIiwiQkFOS19OQU1FIjoiQklEViIsIk1BUF9TQUNPTV9DQVJEIjowLCJOQU1FIjoiQsO5aSBI4buvdSBI4bqtdSIsIklERU5USUZZIjoiQ09ORklSTSIsIkRFVklDRV9PUyI6ImlvcyIsIkFQUF9WRVIiOjQxMDgxLCJhZ2VudF9pZCI6Mzg2MTkyNzQsInNlc3Npb25LZXkiOiI3M1VMTXdNQ3JrUy9yOGpQRjR6Y1dEem8xcFQyZjNGNEMyRkMxOG95c0dSOHFFSzZ4clQzSmc9PSIsInBpbiI6IkFnM2MzUFJtNmYwPSIsImlzU2hvcCI6ZmFsc2UsInVzZXJfdHlwZSI6MSwia2V5IjoibW9tbyIsInJhcGlkX2lkIjoiSGNMNlVaRGFpR1RHa1FOc3pLSUdaWk51VHBjWkZiSzhOVkY0TGtycnVPS0ZxTmg1QkpKVG5kRUNIUVJwRTZNcTNYZU9iMzVhL1FZPSIsInVpZCI6IjA4ODY1NDMzMDEiLCJleHAiOjE3MDE3NzcwNjV9.GUQT87HSohaeTrfYNxvN4ceLiXjsabhLA0S16H4tMWY6nXDMovMlTVcavFym8PclZeBe3yUls5oqnHAwyD5tadmRT4ZQ4T2-BUZvRV5L4FP92DEMXn7x1fXen44klB9Xqv2FgXrSiNWiTnK2qHXRptD4Wx_Q7dMN1fwg9qdf4F6Qhz6ed9j1pK4QHlOG3sWrp_Tbd6_zhStH-IpnNdJe8a6HGbY_XrS3kbcdc_ZsfA3N18EPRzaEjvzVlcBD8m91xewmoF2aKZWULTNVJ9d6hZ2eTrTGIUmm2XaAEhKCNfu55Rl8mbVZjHU1JgvUwC0ljBSDL8UzlG_34T7JY7qHRg',
            'timezone' => ' Asia/Ho_Chi_Minh',
            'ftv' => ' 1&1&1',
            'device_os' => ' IOS',
            'env' => ' production',
            'Accept-Language' => ' vi-VN,vi;q=0.9',
            'Accept-Charset' => ' UTF-8',
            'Accept' => ' application/json',
            'agent_id' => ' 38619274',
            'Content-Type' => ' application/json',
            'Accept-Encoding' => ' gzip, deflate, br',
            'platform-timestamp' =>  time() * 1000
        ];
        $body = [
            "fromTime" => (time() - 60 * 60) * 1000,
            "userId" => "0886543301",
            "toTime" => time() * 1000,
            "limit" => 500
        ];
        $body = json_encode($body);
        try {
            $res = $client->request('POST', 'https://m.mservice.io/hydra/v2/user/noti', [
                'headers' => $headers,
                'body' => $body,
            ]);



            $data = json_decode($res->getBody());
            $data = $data->message->data->notifications;
            echo "<pre>";
            var_dump($data);
            $listMomo = [];
            foreach ($data as $key => $value) {
                if ($value->type == 77) {
                    $listMomo = array_merge($listMomo, [$value]);
                }
            }
            // Đổi giá trị biến trong file config/listmomo.php
            // Đường dẫn đến file services.php
            // Lấy đường dẫn của file cấu hình listmomo.php
            $filePath = config_path('listmomo.php');

            // Kiểm tra xem file tồn tại hay không
            if (File::exists($filePath)) {
                // Đọc nội dung của file
                $content = File::get($filePath);

                // Chuỗi cần thay thế và chuỗi thay thế mới
                $oldListString = config('listmomo.listMomo');
                $newListString = json_encode($listMomo);

                // Kiểm tra xem chuỗi cần thay thế có tồn tại trong nội dung hay không
                if (strpos($content, "'listMomo' => '$oldListString'") !== false) {
                    // Thực hiện thay thế chuỗi
                    $content = str_replace("'listMomo' => '$oldListString'", "'listMomo' => '$newListString'", $content);
                    // $content = str_replace("'listMomo' => '$oldListString'", "'listMomo' => '1245'", $content);

                    // Lưu nội dung đã sửa lại vào file
                    File::put($filePath, $content);

                    $this->info('File listmomo.php has been modified successfully.');
                } else {
                    $this->error('String not found in content.');
                }
            } else {
                $this->error('File not found.');
            }
        } catch (RequestException $e) {
            // Xử lý lỗi
            $response = $e->getResponse();

            if ($response) {
                $statusCode = $response->getStatusCode();
                $errorBody = $response->getBody()->getContents();
                $this->error($errorBody);
                // $this->info(config('services.momo.token'));
            }
            // $this->refreshTokenMomo();
        }
    }

    function refreshTokenMomo()
    {
        $client = new Client();
        $headers = [
            'Host' => ' api.momo.vn',
            'userId' => ' 0886543301',
            'MsgType' => ' REFRESH_TOKEN_MSG',
            'user_phone' => ' 0886543301',
            'app_code' => ' 4.1.8',
            'User-Agent' => ' MoMoPlatform Store/4.1.8.41081 CFNetwork/1402.0.8 Darwin/22.2.0 (iPhone 11 iOS/16.2) AgentID/38619274',
            'lang' => ' vi',
            'app_version' => ' 41081',
            'Cookie' => ' _tt_enable_cookie=1; _ttp=ci73U_UCroTL87BiEhKypnrBEts; _ga_HT2QMKNGGM=GS1.2.1692623932.1.0.1692623932.60.0.0; _ga=GA1.2.215838029.1687696759; _ga_9W5WQV1PWM=GS1.1.1692623931.2.0.1692623931.60.0.0; _ga_WF5M6562PW=GS1.1.1692623931.2.0.1692623931.0.0.0; _ga_R9DY9WEP3J=GS1.1.1692623930.1.0.1692623930.0.0.0',
            'sessionKey' => ' e01c9308-6531-4f82-b703-dc1d8fa7c7a6',
            'channel' => ' APP',
            'momo-session-key-tracking' => ' C4DB7E85-8EB4-413C-BB1E-6B569F6DFD41',
            'Content-Length' => ' 2104',
            'Connection' => ' keep-alive',
            'Authorization' => ' Bearer ' . config('services.momo.refresh_token'),
            'timezone' => ' Asia/Ho_Chi_Minh',
            'env' => ' production',
            'device_os' => ' IOS',
            'Accept-Language' => ' vi-VN,vi;q=0.9',
            'Accept-Charset' => ' UTF-8',
            'Accept' => ' application/json',
            'Content-Type' => ' application/json',
            'agent_id' => ' 38619274',
            'Accept-Encoding' => ' gzip, deflate, br',
            'platform-timestamp' => time() * 1000
        ];
        $body = '{"user":"0886543301","msgType":"REFRESH_TOKEN_MSG","momoMsg":{"_class":"mservice.backend.entity.msg.RefreshAccessTokenMsg","accessToken":"eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJ1c2VyIjoiMDg4NjU0MzMwMSIsImltZWkiOiIxYTcwNWM5ODRhNjczMzI2YjUzYzliYzNiNmFlYmQ1NzY1OWFiYmFkYWVjMjFlMTc3ZjZjYTZiY2MzNWNhYmJiIiwiQkFOS19DT0RFIjoiMTEwIiwiQkFOS19OQU1FIjoiQklEViIsIk1BUF9TQUNPTV9DQVJEIjowLCJOQU1FIjoiQsO5aSBI4buvdSBI4bqtdSIsIklERU5USUZZIjoiQ09ORklSTSIsIkRFVklDRV9PUyI6ImlvcyIsIkFQUF9WRVIiOjQxMDgxLCJhZ2VudF9pZCI6Mzg2MTkyNzQsInNlc3Npb25LZXkiOiJxdHhaeGU1U013WEppdkZzSXlzNndiOHl6VXg5UmpqSnBDWjE3QXMyZ2h6S2d6K3ZIV2ZLVFE9PSIsInBpbiI6IkFnM2MzUFJtNmYwPSIsImlzU2hvcCI6ZmFsc2UsInVzZXJfdHlwZSI6MSwia2V5IjoibW9tbyIsInJhcGlkX2lkIjoielNQdnlsWkprNVJaYVZyYUFQNHY2ZWZGZEthZmJLdVFMb3psSi96aWJIaUJvaWRkZXU4cFpCaWY3YmdvL21PZFJWbU5HMGx6U0FzPSIsImV4cCI6MTcwMDUzMzY0MH0.f8zqTihZC2pmiS-VZdtD7E0z7LcaDKJ5FeYxfxkprFehLb48pYqeC5L5F-qEL6GvHt7aR_1u3AfoNqd_OooHnxJOSG4uU15da7_jqpE1RPDSkDHpEuqIvQL8-ApDSbSAnrJ_C1P8VVMduO0fHZAMgq12R6a_sU4wgmzMpVFQ61khBnB4sfuH3yVjPfeKdqWF86Kr-Ja_9P7zp5hDYu3fVa-Oa54XJ1g50KdM1uL9h9DZWiOLPgk-SzgLUqseknERyExHSgRktENOiem0sVCvX08rxlSF2xqFf6bKe6S2eBx6wQzL1831fQf4z0ExMB66ngVCSgEgXMT5f_9lFfDj6Q"},"extra":{"IDFA":"","SIMULATOR":false,"TOKEN":"dMVoVzq5JkCEhIY8DPpENC:APA91bHzcDFfvT3Qw-qkRRAdrBElNYymexNwD2ve118dD_aZk72kQyslT6uF4o7mPKZ5W_n3m2jYLGijVQ5EhqnGgsKs8EfEPmyT8y4VTA0ipQ28wd4l66YFYkvglA5nOapyX1u1XOKJ","ONESIGNAL_TOKEN":"dMVoVzq5JkCEhIY8DPpENC:APA91bHzcDFfvT3Qw-qkRRAdrBElNYymexNwD2ve118dD_aZk72kQyslT6uF4o7mPKZ5W_n3m2jYLGijVQ5EhqnGgsKs8EfEPmyT8y4VTA0ipQ28wd4l66YFYkvglA5nOapyX1u1XOKJ","SECUREID":"","MODELID":"54c6785a98c13e56c81c8cb46345e16657a1234b01034ca77b4af3ce73ea3ddc","DEVICE_TOKEN":"F367B329E9A47A02AADEBFF02C6F056144628A1BAE0EE3452F14AEC4D55B1B56","DEVICE_IMEI":"1a705c984a673326b53c9bc3b6aebd57659abbadaec21e177f6ca6bcc35cabbb","checkSum":"xOSy8KUFWi/7XQZhABaGMsxbpj5SXVqDOQntG6IcUmE+72fDmwQHa2ozhh0+ns1lOazpeZa9FDhh72IGG9kfqg=="},"appVer":41081,"appCode":"4.1.8","lang":"vi","deviceOS":"ios","channel":"APP","buildNumber":0,"appId":"vn.momo.platform","cmdId":"1700528252529000000","time":1700528252529}';
        $res = $client->request('POST', 'https://api.momo.vn/auth/fast-login/refresh-token', [
            'headers' => $headers,
            'body' => $body,
        ]);
        $data = json_decode($res->getBody());
        $token = $data->momoMsg->accessToken;

        // Đường dẫn đến file services.php
        $filePath = config_path('services.php');

        // Đọc nội dung của file services.php
        $content = File::get($filePath);

        // Chỉnh sửa cấu hình theo
        $old_token = config('services.momo.token');
        $content = str_replace("'token' => '$old_token'", "'token' => '$token'", $content);

        // Lưu nội dung đã sửa lại vào file
        File::put($filePath, $content);
        $this->info('Refresh token successfully.');
    }
}
