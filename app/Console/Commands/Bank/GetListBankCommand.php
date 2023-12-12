<?php

namespace App\Console\Commands\Bank;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class GetListBank extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bank:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get list bank';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->getListBank();
    }

    protected function getListBank()
    {
        $client = new Client();
        $headers = [
            'Accept' => ' application/json, text/plain',
            'Accept-Encoding' => ' gzip, deflate, br',
            'Accept-Language' => ' vi,en-GB;q=0.9,en-US;q=0.8,en;q=0.7,ja;q=0.6,en-GB-oxendict;q=0.5',
            'Connection' => ' keep-alive',
            'Content-Length' => ' 135',
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
            'token' => ' ' . config('services.bank.token'),
            'x-gofs-context-id' => ' a4486d1cdc96078ddc9840c4a8f24363e6d18c582ea4a2fbb4bd88b69c9db2b1.733d1595-1ee8-40eb-be6a-c0895d0c7643',
            'x-timo-devicekey' => ' 89deAjCa:WEB:WEB:194:WEB:mobile:chrome'
        ];
        $toDate = Carbon::now('Asia/Ho_Chi_Minh')->format('d/m/Y');
        $body = [
            "format" => "group",
            "index" => 0,
            "offset" => -1,
            "accountNo" => "9021607195160",
            "accountType" => "1025",
            "fromDate" => "01/01/2015",
            "toDate" => $toDate
        ];
        $body = json_encode($body);
        try {
            $res = $client->request('POST', 'https://app2.timo.vn/user/account/transaction/list', [
                'headers' => $headers,
                'body' => $body,
            ]);

            $data = json_decode($res->getBody());
            $data = $data->data->items;

            $listBank = [];
            foreach ($data as $items) {
                foreach ($items->item as $key => $value) {
                    if (($value->rawTxnType == 213) && ($value->txnType === "IncomingTransfer")) {
                        $listBank = array_merge($listBank, [$value]);
                    }
                }
            }
            $this->info(count($listBank));
            // Đổi giá trị biến trong file config/listmomo.php
            // Đường dẫn đến file services.php
            // Lấy đường dẫn của file cấu hình listmomo.php
            $filePath = config_path('listbank.php');

            // Kiểm tra xem file tồn tại hay không
            if (File::exists($filePath)) {
                // Đọc nội dung của file
                $content = File::get($filePath);

                // Chuỗi cần thay thế và chuỗi thay thế mới
                $oldListString = config('listbank.listBank');
                $newListString = json_encode($listBank);

                // Kiểm tra xem chuỗi cần thay thế có tồn tại trong nội dung hay không
                if (strpos($content, "'listBank' => '$oldListString'") !== false) {
                    // Thực hiện thay thế chuỗi
                    $content = str_replace("'listBank' => '$oldListString'", "'listBank' => '$newListString'", $content);
                    // $content = str_replace("'listMomo' => '$oldListString'", "'listMomo' => '1245'", $content);

                    // Lưu nội dung đã sửa lại vào file
                    File::put($filePath, $content);

                    $this->info('File listmomo.php has been modified successfully.');
                } else {
                    $this->error('String not found in content.');
                }
            } else {
                // Nếu file confi/listBank không tồn tại, tạo nó
                $content = "<?php\n\nreturn [\n    'listBank' => '[]'\n];";
                file_put_contents($filePath, $content);
            }
        } catch (RequestException $e) {
            // Xử lý lỗi
            $response = $e->getResponse();
            
            // $this->error($e);
            
            if ($response) {
                $statusCode = $response->getStatusCode();
                if($statusCode === 401){
                    $this->call('bank:get-token');
                } else {
                    $reasonPhrase = $response->getReasonPhrase();
                    $this->error("HTTP status code {$statusCode}: {$reasonPhrase}");
                }
                // $errorBody = $response->getBody()->getContents();
                // $this->error($errorBody);
            }
        }
    }
}
