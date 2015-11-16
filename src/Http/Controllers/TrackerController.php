<?php namespace FrenchFrogs\Tracker;

use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Request;
use Models\Business\Tracking;
use FrenchFrogs\Models\Business\Mail;

class TrackerController extends Controller
{
    public function tracking()
    {
        // if we have a hash in get parameters and it is active, we create a new tracking log
        if (Request::has('h')) {
            $track = Tracking::getByHash(Request::input('h'));
            if ($track->isActive()) {
                $data = [
                    'tracking_id' => $track->tracking_id,
                    'data' => json_encode(Request::except('h'))
                ];
                Tracking::log($data);
            }
        }

        // if we have a mail_id, we update the mail status and the date where it is opened
        if (Request::has(Tracking::MAIL_ID)) {
            if (Mail::exists(Request::input(Tracking::MAIL_ID))) {
                $mail = Mail::getById(Request::input(Tracking::MAIL_ID));
                $mail->mail_status_id = Mail::STATUS_OPENED;
                $mail->opened_at = Carbon::now();
                $mail->save();
            }
        }

        header('Content-type:image/jpg');
        header("Pragma: no-cache");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0, public");
        header("Expires: 0");
        exit;
    }
}