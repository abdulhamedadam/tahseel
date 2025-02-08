<?php
// legacy FCM APIs
function send_notification_FCM_old($notification_id, $title, $message, $id, $type)
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
// the HTTP v1 API

use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\OAuth2;

function getAccessToken()
{
    $client = new \Google\Client();
    $client->setAuthConfig(storage_path('rashaktekapp-firebase-adminsdk-dcz11-5055e6ab69.json'));
    $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
    $client->fetchAccessTokenWithAssertion();

    return $client->getAccessToken()['access_token'];
}

function send_notification_FCM($notification_id, $title, $message, $id, $type)
{
    $accessToken = getAccessToken();
    $projectID = 'rashaktekapp';
    $url = 'https://fcm.googleapis.com/v1/projects/' . $projectID . '/messages:send';
    $post_data = [
        'message' => [
            'token' => $notification_id,
            'data' => [
                'title' => (string) $title,
                'type' => (string) $type,
                'id' => (string) $id,
                'message' => (string) $message
            ],
            'notification' => [
                'body' => (string) $message,
                'title' => (string) $title
            ]
        ]
    ];

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $accessToken
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));

    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}


/*-------------------------------------------------------*/
function add_notifications($to_user_id, $to_user_name, $from_user_id, $from_user_name, $content, $title, $action, $status, $type)
{
    $notification = new \App\Models\Notifications;
    $notification->to_user_id = $to_user_id;
    $notification->to_user_name = $to_user_name;
    $notification->from_user_id = $from_user_id;
    $notification->from_user_name = $from_user_name;
    $notification->content = json_encode($content);
    $notification->title = json_encode($title);
    // $notification->action = $action;
    $notification->status = $status;
    $notification->type = $type;
    $notification->add_at_day = now()->format('Y-m-d');
    $notification->add_at_time = now()->format('H:i:s');
//dd($notification);
    $notification->save();
    return $notification;
}

/*---------------------------------------------------------------------------*/
function send_notifications($to_user_id, $to_user_name, $from_user_id, $from_user_name, $data, $extra_data, $status, $type, $token, $code)
{
    switch ($code) {
        case 1:
            $title_ar = 'تم تحديث نظامك الغذائي';
            $title_en = 'Your diet plan has been updated.';
            $title = ['ar' => $title_ar, 'en' => $title_en];
            $content_en = $from_user_name . '  has updated your diet  ' ;
            $content_ar = $from_user_name . ' قام بتحديث نظامك الغذائي  ';
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
            $title_ar = ' تم تحديث الinbody الخاص بك';
            $title_en = 'Your inbody  has been updated. ';
            $title = ['ar' => $title_ar, 'en' => $title_en];
            $content_en = $from_user_name . '  has updated your inbody ';
            $content_ar = $from_user_name . ' قام بتحديث inbody الخاص بك '  ;
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
            $title_ar = 'استخدام كود الدعوة الخاص بك';
            $title_en = 'Use your invitation code';
            $title = ['ar' => $title_ar, 'en' => $title_en];
            $content_en = $from_user_name . '  has used your  Invitation Code To Subscription';
            $content_ar = $from_user_name .'استخدم كود الدعوة الخاص بك للإشتراك ';
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
            $title_ar = 'إضافة class جديد';
            $title_en = 'Add new Class ';
            $title = ['ar' => $title_ar, 'en' => $title_en];
            $content_en = $from_user_name . ' A class has been created';
            $content_ar = 'تم إضافة class جديد' . $from_user_name;
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

        /*---------------------------------------------*/

    }
    add_notifications($to_user_id, $to_user_name, $from_user_id, $from_user_name, $content, $title, $extra_data, $status, $type);
   // dd($extra_data);
    send_notification_FCM($token, $title_fcm, $message, $extra_data['0'], $type);
}

/***********************************************************************************************************/
function send_notifications2($to_user_id, $to_user_name, $from_user_id, $from_user_name, $data, $extra_data, $status, $type, $token, $code)
{

    $notificationData = [
        1 => [
            'title' => ['ar' => 'انضمام للتمرين', 'en' => 'exercise join request'],
            'content' => [
                'en' => "$from_user_name has sent a request to join exercise {$data[1]} at day {$data[2]} ",
                'ar' => "$from_user_name ارسل لك طلب انظمام للتمرين {$data[1]} ليوم {$data[2]}"
            ]
        ],
        2 => [
            'title' => ['ar' => 'رفض انضمام للتمرين', 'en' => 'exercise join accept'],
            'content' => [
                'en' => "$from_user_name refused request to join exercise {$data[1]} at day {$data[2]}",
                'ar' => "$data[2] ليوم {$data[1]} رفض على طلب الانضمام على رحلة $from_user_name"
            ]
        ],
        4 => [
            'title' => ['ar' => 'بداية التمرين', 'en' => 'Start of exercise'],
            'content' => [
                'en' => "$from_user_name  has started exercising. {$data[1]}",
                'ar' => "$data[1]  بدأ التمرين $from_user_name"
            ]
        ],
        5 => [
            'title' => ['ar' => 'عمل تعليق', 'en' => 'Make a comment'],
            'content' => [
                'en' => "$from_user_name commented {$data[1]}",
                'ar' => "$data[1] نشر تعليق $from_user_name"
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

        add_notifications($to_user_id, $to_user_name, $from_user_id, $from_user_name, $content, $title, $extra_data, $status, $code);
        return   send_notification_FCM($token, $title_fcm, $message, $extra_data[0], $type);
    }
}
