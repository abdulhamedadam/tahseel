<?php

function send_notification_FCM($notification_id, $title, $message, $id, $type)
{
    $accesstoken = env('FCM_KEY');
    $URL = 'https://fcm.googleapis.com/fcm/send';

    $post_data = '{
        "to" : "' . $notification_id . '",
        "data" : {
            "title" : "' . $title . '",
            "type" : "' . $type . '",
            "id" : "' . $id . '",
            "message" : "' . $message . '"
        },
        "notification" : {
            "body" : "' . $message . '",
            "title" : "' . $title . '",
            "type" : "' . $type . '",
            "id" : "' . $id . '",
            "message" : "' . $message . '",
            "icon" : "new",
            "sound" : "default"
        }
    }';

    $crl = curl_init();

    $headr = array();
    $headr[] = 'Content-type: application/json';
    $headr[] = 'Authorization: key=' . $accesstoken;

    curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($crl, CURLOPT_URL, $URL);
    curl_setopt($crl, CURLOPT_HTTPHEADER, $headr);
    curl_setopt($crl, CURLOPT_POST, true);
    curl_setopt($crl, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);

    $rest = curl_exec($crl);

    /*   if ($rest === false) {

           return 0;
       } else {

           return 1;
       }*/

    curl_close($crl);
    return $rest;
}


/*-------------------------------------------------------*/
function add_notifications($to_user_id, $to_user_name, $from_user_id, $from_user_name, $content, $title, $action, $status, $type)
{
    $notification = new \App\Models\Notifications;
    $notification->to_user_id = $to_user_id;
    $notification->to_user_name = $to_user_name;
    $notification->from_user_id = $from_user_id;
    $notification->from_user_name = $from_user_name;
    $notification->content = $content;
    $notification->title = $title;
    $notification->action = $action;
    $notification->status = $status;
    $notification->type = $type;
    $notification->add_at_day = now()->format('Y-m-d');
    $notification->add_at_time = now()->format('H:i:s');

    $notification->save();
    return $notification;
}

/*---------------------------------------------------------------------------*/
function send_notifications($to_user_id, $to_user_name, $from_user_id, $from_user_name, $data, $extra_data, $status, $type, $token, $code)
{
    switch ($code) {
        case 1:
            $title_ar = 'انضمام للرحلة ';
            $title_en = 'trip join request';
            $title = ['ar' => $title_ar, 'en' => $title_en];
            $content_en = $from_user_name . '  has sent request to join trip  ' . $data[1] . '   at day  ' . $data[2];
            $content_ar = $from_user_name . ' ارسل لك طلب انظام للرحلة  ' . $data[1] . ' ليوم ' . $data[2];
            $content = ['ar' => $content_ar, 'en' => $content_en];
            if ($data[0] == 'en') {
                $title_fcm = $title_en;
                $message = $content_en;
            } elseif ($data[0] == 'ar') {
                $title_fcm = $title_ar;
                $message = $content_ar;
            }
            break;
        /*---------------------------------------------*/

        case 2:
            $title_ar = ' موافقة انضمام للرحلة ';
            $title_en = ' trip join accept ';
            $title = ['ar' => $title_ar, 'en' => $title_en];
            $content_en = $from_user_name . '   accepted request to join trip ' . $data[1] . '  at day ' . $data[2];
            $content_ar = $data[2] . 'ليوم' . $data[1] . ' وافق علي طلب الانضمام علي رحلة ' . $from_user_name;
            $content = ['ar' => $content_ar, 'en' => $content_en];

            if ($data[0] == 'en') {
                $title_fcm = $title_en;
                $message = $content_en;
            } elseif ($data[0] == 'ar') {
                $title_fcm = $title_ar;
                $message = $content_ar;
            }
            /*---------------------------------------------*/
            break;
        case 3:
            $title_ar = ' رفض انضمام للرحلة ';
            $title_en = ' trip join refuse ';
            $title = ['ar' => $title_ar, 'en' => $title_en];
            $content_en = $from_user_name . '  refused request to join trip ' . $data[1] . '  at day ' . $data[2];
            $content_ar = $data[2] . 'ليوم' . $data[1] . ' رفض علي طلب الانضمام علي رحلة ' . $from_user_name;
            $content = ['ar' => $content_ar, 'en' => $content_en];
            if ($data[0] == 'en') {
                $title_fcm = $title_en;
                $message = $content_en;
            } elseif ($data[0] == 'ar') {
                $title_fcm = $title_ar;
                $message = $content_ar;
            }

            /*---------------------------------------------*/
            break;
        case 4:
            $title_ar = ' دعوة انضمام لجروب ';
            $title_en = ' groub join request ';
            $title = ['ar' => $title_ar, 'en' => $title_en];
            $content_en = $from_user_name . '  has sent request to join groub ' . $data[1];
            $content_ar = $data[1] . ' ارسل لك طلب انظام للرحلة ' . $from_user_name;
            $content = ['ar' => $content_ar, 'en' => $content_en];
            if ($data[0] == 'en') {
                $title_fcm = $title_en;
                $message = $content_en;
            } elseif ($data[0] == 'ar') {
                $title_fcm = $title_ar;
                $message = $content_ar;
            }

            /*---------------------------------------------*/
            break;
        case 5:
            $title_ar = ' موافقة انضمام لجروب ';
            $title_en = ' groub join accept ';
            $title = ['ar' => $title_ar, 'en' => $title_en];
            $content_en = $from_user_name . ' accepted request to join groub ' . $data[1];
            $content_ar = $data[1] . 'وافق علي طلب الانضمام لجروب ' . $from_user_name;
            $content = ['ar' => $content_ar, 'en' => $content_en];
            if ($data[0] == 'en') {
                $title_fcm = $title_en;
                $message = $content_en;
            } elseif ($data[0] == 'ar') {
                $title_fcm = $title_ar;
                $message = $content_ar;
            }

            /*---------------------------------------------*/
            break;
        case 6:
            $title_ar = ' رفض انضمام لجروب ';
            $title_en = ' groub join refuse ';
            $title = ['ar' => $title_ar, 'en' => $title_en];
            $content_en = $from_user_name . ' refused request to join groub ' . $data[1];
            $content_ar = $data[1] . 'رفض علي طلب الانضمام لجروب ' . $from_user_name;
            $content = ['ar' => $content_ar, 'en' => $content_en];
            if ($data[0] == 'en') {
                $title_fcm = $title_en;
                $message = $content_en;
            } elseif ($data[0] == 'ar') {
                $title_fcm = $title_ar;
                $message = $content_ar;
            }
            break;
        /*---------------------------------------------*/

        case 7:
            $title_ar = 'انهاءالرحلة ';
            $title_en = ' end trip ';
            $title = ['ar' => $title_ar, 'en' => $title_en];
            $content_en = $from_user_name . '  has ended trip  ' . $data[1] . '   at day  ' . $data[2];
            $content_ar = $from_user_name . ' انهى الرحلة  ' . $data[1] . ' ليوم ' . $data[2];
            $content = ['ar' => $content_ar, 'en' => $content_en];
            if ($data[0] == 'en') {
                $title_fcm = $title_en;
                $message = $content_en;
            } elseif ($data[0] == 'ar') {
                $title_fcm = $title_ar;
                $message = $content_ar;
            }
            break;
        /*---------------------------------------------*/

        case 8:
            $title_ar = 'بداية الرحلة ';
            $title_en = ' start trip ';
            $title = ['ar' => $title_ar, 'en' => $title_en];
            $content_en = $from_user_name . '  has started trip  ' . $data[1] . '   at day  ' . $data[2];
            $content_ar = $from_user_name . ' قام بدأ الرحلة  ' . $data[1] . ' ليوم ' . $data[2];
            $content = ['ar' => $content_ar, 'en' => $content_en];
            if ($data[0] == 'en') {
                $title_fcm = $title_en;
                $message = $content_en;
            } elseif ($data[0] == 'ar') {
                $title_fcm = $title_ar;
                $message = $content_ar;
            }
            break;
        /*---------------------------------------------*/

    }
    add_notifications($to_user_id, $to_user_name, $from_user_id, $from_user_name, $content, $title, $extra_data, $status, $type);
    send_notification_FCM($token, $title_fcm, $message, $extra_data['0'], $type);
}

/***********************************************************************************************************/
function send_notifications2($to_user_id, $to_user_name, $from_user_id, $from_user_name, $data, $extra_data, $status, $type, $token, $code)
{

    $notificationData = [
        1 => [
            'title' => ['ar' => 'انضمام للرحلة', 'en' => 'trip join request'],
            'content' => [
                'en' => "$from_user_name has sent a request to join trip {$data[1]} at day {$data[2]} ",
                'ar' => "$from_user_name ارسل لك طلب انظمام للرحلة {$data[1]} ليوم {$data[2]}"
            ]
        ],
        2 => [
            'title' => ['ar' => 'موافقة انضمام للرحلة', 'en' => 'trip join accept'],
            'content' => [
                'en' => "$from_user_name trip {$data[1]} at day {$data[2]}",
                'ar' => "$data[2] ليوم {$data[1]} وافق على طلب الانضمام على رحلة $from_user_name"
            ]
        ],
        3 => [
            'title' => ['ar' => 'رفض انضمام للرحلة', 'en' => 'trip join accept'],
            'content' => [
                'en' => "$from_user_name refused request to join trip {$data[1]} at day {$data[2]}",
                'ar' => "$data[2] ليوم {$data[1]} رفض على طلب الانضمام على رحلة $from_user_name"
            ]
        ],
        4 => [
            'title' => ['ar' => 'دعوة انضمام لجروب', 'en' => 'groub join request'],
            'content' => [
                'en' => "$from_user_name has sent a request to join groub {$data[1]}",
                'ar' => "$data[1] ارسل لك طلب انظام للرحلة $from_user_name"
            ]
        ],
        5 => [
            'title' => ['ar' => 'موافقة انضمام لجروب', 'en' => 'groub join accept'],
            'content' => [
                'en' => "$from_user_name accepted request to join groub {$data[1]}",
                'ar' => "$data[1] وافق على طلب الانضمام لجروب $from_user_name"
            ]
        ],
        6 => [
            'title' => ['ar' => 'رفض انضمام لجروب', 'en' => 'groub join refuse'],
            'content' => [
                'en' => "$from_user_name refused request to join groub {$data[1]}",
                'ar' => "$data[1] رفض على طلب الانضمام لجروب $from_user_name"
            ]
        ],
        9 => [
            'title' => ['ar' => 'دعوة انضمام لرحلة', 'en' => 'Trip Invitation Request '],
            'content' => [
                'en' => "$from_user_name Sent you invitation to join trip {$data[1]}",
                'ar' => "$data[1] أرسل اليك دعوة لانضمام لرحلة  $from_user_name"
            ]
        ],
        10 => [
            'title' => ['ar' => 'دعوة انضمام لرحلة', 'en' => ' Trip Invitation Request '],
            'content' => [
                'en' => "$from_user_name refused your invitation to join trip {$data[1]}",
                'ar' => "$data[1] تم رفض  دعوة لانضمام لرحلة  $from_user_name"
            ]
        ],
        11 => [
            'title' => ['ar' => ' دعوة انضمام لرحلة', 'en' => 'Trip Invitation Request '],
            'content' => [
                'en' => "$from_user_name accept your invitation to join trip {$data[1]}",
                'ar' => "$data[1] تم قبول دعوة لانضمام لرحلة  $from_user_name"
            ]
        ]
    ];

    if (isset($notificationData[$code])) {
        $titleData = $notificationData[$code]['title'];
        $contentData = $notificationData[$code]['content'];

        $title = ['ar' => $titleData['ar'], 'en' => $titleData['en']];
        $content = ['ar' => $contentData['ar'], 'en' => $contentData['en']];
        $title_fcm = $data[0] == 'en' ? $titleData['en'] : $titleData['ar'];
        $message = $data[0] == 'en' ? $contentData['en'] : $contentData['ar'];

        add_notifications($to_user_id, $to_user_name, $from_user_id, $from_user_name, $content, $title, $extra_data, $status, $type);
        send_notification_FCM($token, $title_fcm, $message, $extra_data[0], $type);
    }
}

