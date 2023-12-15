<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => 'http://localhost/ticketBookingCar/public/api/login/google/callback',
    ],

    'momo' => [
        'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJ1c2VyIjoiMDg4NjU0MzMwMSIsImltZWkiOiIxYTcwNWM5ODRhNjczMzI2YjUzYzliYzNiNmFlYmQ1NzY1OWFiYmFkYWVjMjFlMTc3ZjZjYTZiY2MzNWNhYmJiIiwiQkFOS19DT0RFIjoiMTEwIiwiQkFOS19OQU1FIjoiQklEViIsIk1BUF9TQUNPTV9DQVJEIjowLCJOQU1FIjoiQsO5aSBI4buvdSBI4bqtdSIsIklERU5USUZZIjoiQ09ORklSTSIsIkRFVklDRV9PUyI6ImlvcyIsIkFQUF9WRVIiOjQxMDgxLCJhZ2VudF9pZCI6Mzg2MTkyNzQsInNlc3Npb25LZXkiOiJxdHhaeGU1U013WEppdkZzSXlzNndiOHl6VXg5UmpqSnBDWjE3QXMyZ2h6S2d6K3ZIV2ZLVFE9PSIsInBpbiI6IkFnM2MzUFJtNmYwPSIsImlzU2hvcCI6ZmFsc2UsInVzZXJfdHlwZSI6MSwia2V5IjoibW9tbyIsInJhcGlkX2lkIjoielNQdnlsWkprNVJaYVZyYUFQNHY2ZWZGZEthZmJLdVFMb3psSi96aWJIaUJvaWRkZXU4cFpCaWY3YmdvL21PZFJWbU5HMGx6U0FzPSIsImV4cCI6MTcwMDUzMzY0MH0.f8zqTihZC2pmiS-VZdtD7E0z7LcaDKJ5FeYxfxkprFehLb48pYqeC5L5F-qEL6GvHt7aR_1u3AfoNqd_OooHnxJOSG4uU15da7_jqpE1RPDSkDHpEuqIvQL8-ApDSbSAnrJ_C1P8VVMduO0fHZAMgq12R6a_sU4wgmzMpVFQ61khBnB4sfuH3yVjPfeKdqWF86Kr-Ja_9P7zp5hDYu3fVa-Oa54XJ1g50KdM1uL9h9DZWiOLPgk-SzgLUqseknERyExHSgRktENOiem0sVCvX08rxlSF2xqFf6bKe6S2eBx6wQzL1831fQf4z0ExMB66ngVCSgEgXMT5f_9lFfDj6Q',
        'refresh_token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJFUzI1NiJ9.eyJ1c2VyIjoiMDg4NjU0MzMwMSIsImltZWkiOiIxYTcwNWM5ODRhNjczMzI2YjUzYzliYzNiNmFlYmQ1NzY1OWFiYmFkYWVjMjFlMTc3ZjZjYTZiY2MzNWNhYmJiIiwiQkFOS19DT0RFIjoiMTEwIiwiQkFOS19OQU1FIjoiQklEViIsIk1BUF9TQUNPTV9DQVJEIjowLCJOQU1FIjoiQsO5aSBI4buvdSBI4bqtdSIsIklERU5USUZZIjoiQ09ORklSTSIsIkRFVklDRV9PUyI6ImlvcyIsIkFQUF9WRVIiOjQxMDgxLCJhZ2VudF9pZCI6Mzg2MTkyNzQsInNlc3Npb25LZXkiOiI3M1VMTXdNQ3JrUy9yOGpQRjR6Y1dEem8xcFQyZjNGNEMyRkMxOG95c0dUV3RmWnZIKzN1bGtRajRBRTlaVnYrblpaSkppWHBkVmM9IiwicGluIjoiQWczYzNQUm02ZjA9IiwiaXNTaG9wIjpmYWxzZSwidXNlcl90eXBlIjoxLCJrZXkiOiJtb21vLXJlZnJlc2gtdG9rZW4iLCJyYXBpZF9pZCI6IkhjTDZVWkRhaUdUR2tRTnN6S0lHWlpOdVRwY1pGYks4TlZGNExrcnJ1T0tGcU5oNUJKSlRuZEVDSFFScEU2TXEzWGVPYjM1YS9RWT0iLCJ1aWQiOiIwODg2NTQzMzAxIiwiZXhwIjoxNzAyMTMyMjI5fQ.pIT-rJgnL_WkGaGaB6FLvL5VokUnYmupmRppxtvuv2qfnCwGBkcY15y6wT24W0xGRMAULakzjM7y7vpeMG5rBw'
    ],

    'bank' => [
        'token' => 'eyJraWQiOiI3YjA5NDUwOS1kOGQ2LTQzZmQtYjZmYS1iNGJhNzk2NzJiZDIiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOiJ6OUJILVBGalZuRVUtQ3AySlBPXzN3IiwidWlkIjoiM2YyZTlkYmEtMmIxNS00ZTJmLWEzNzMtNmQ5YjQ5OWNiY2VjIn0.-radJYV_9joILvocHWtjNr22ycVbEazRg0HFEundvA8',
    ],
];
