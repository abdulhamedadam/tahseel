<?php



use App\Traits\ImageProcessing;
use Illuminate\Support\Facades\Storage;


if (!function_exists('getDefultImage')) {

    function getDefultImage()
    {
        return asset('assets/media/avatars/blank.png');
    }
}
if (!function_exists('getMainData')) {

    function getMainData()
    {
        $mdata = \App\Models\Site\SiteData::first();
        return ($mdata);
    }
}
if (!function_exists('extractVideoId')) {

     function extractVideoId($videoLink)
    {
        // Extract video ID from the YouTube link
        $pattern = '/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
        preg_match($pattern, $videoLink, $matches);

        // Check if the regex matched and return the video ID or null
        return isset($matches[1]) ? $matches[1] : null;
    }
}

if (!function_exists('formatDateForDisplay')) {


    function formatDateForDisplay($dateTimeStr)
    {
        $dateTime = new DateTime($dateTimeStr);

        $formattedDate = $dateTime->format('d M Y');
        $formattedTime = $dateTime->format('g:ia');
        $formattedTime = strtolower($formattedTime);

        return $formattedDate . ' at ' . $formattedTime;
    }
}
if (!function_exists('formatTimeForDisplay')) {


    function formatTimeForDisplay($dateTimeStr)
    {
        $dateTime = new DateTime($dateTimeStr);

        $formattedTime = $dateTime->format('g:i a');
        $formattedTime = strtolower($formattedTime);

        return  $formattedTime;
    }
}
if (!function_exists('formatDateDayDisplay')) {


    function formatDateDayDisplay($dateTimeStr)
    {
        $dateTime = new DateTime($dateTimeStr);

        $formattedDate = $dateTime->format('Y-m-d');

        return $formattedDate;
    }
}


if (!function_exists('getFirstLetters')) {
    function getFirstLetters($inputString)
    {
        $words = explode(' ', $inputString);
        $firstLetters = '';

        foreach ($words as $word) {
            if (!empty($word)) {
                $firstLetters .= strtoupper($word[0]);  // Get the first letter and convert to uppercase
            }
        }

        return $firstLetters;
    }


}

if (!function_exists('generateUniqueRandomCode')) {

    function generateUniqueRandomCode($table, $column)
    {
        do {
            $code = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
            $exists = DB::table($table)->where($column, $code)->exists();
        } while ($exists);

        return $code;
    }
}



/*************************************************************/
function get_session_attendance($member_id,$additional_sub_id)
{
    if ($member_id && $additional_sub_id){
        $session_num=\App\Models\MembersAttendance::where('member_id',$member_id)->where('additional_subscription_id',$additional_sub_id)->count();
        return $session_num;
    }
    else{
        return 0;
    }

}
