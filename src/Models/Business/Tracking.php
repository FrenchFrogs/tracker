<?php namespace FrenchFrogs\Models\Business;

use Carbon\Carbon;
use Agent;
use FrenchFrogs\Business\Business;
use FrenchFrogs\Models\Db\Tracking\Tracking as ModelTracking;
use FrenchFrogs\Models\Db\Tracking\Log as TrackingLog;
use Request;

class Tracking extends Business
{
    const HASH_MAIL_DEFAULT = 'KsJsRZP9IF';
    const MAIL_ID = "internal_mail_id";
    static protected $modelClass = ModelTracking::class;

    /**
     * Get
     *
     * @param $hash
     * @return ModelTracking
     */
    public static function getByHash($hash)
    {
        return ModelTracking::where('tracking_hash', '=', $hash)->firstOrFail();
    }

    /**
     * Create a new tracking
     *
     * @param array $data
     * @return $this
     */
    public static function create(array $data)
    {
        $data['tracking_hash'] = ModelTracking::generateHash();
        $data['created_at'] = Carbon::now();

        return parent::create($data);
    }

    /**
     * Create new log
     *
     * @param $data
     * @return TrackingLog
     */
    public static function log($data)
    {
        $log = new TrackingLog();
        $log->tracking_id = $data['tracking_id'];
        $log->tracking_data = $data['data'];
        $log->ip = Request::ip();
        $log->browser = Agent::browser();
        $log->useragent = Agent::getUserAgent();
        $log->is_mobile = Agent::isMobile();
        $log->is_tablet = Agent::isTablet();
        $log->created_at = Carbon::now();
        $log->save();

        return $log;
    }

    /**
     * Generation of pixel tracking
     *
     * @param $track_hash
     * @param array $query
     * @return string
     */
    static function generatePixel($track_hash, $query = [])
    {
        // create url of tracking
        $query['h'] = $track_hash;

        $url = route('tracking', $query);

        // return the pixel
        return sprintf('<img src="%s" width="1" height="1" />', $url);
    }


    /**
     * Generation of pixel tracking for email
     *
     * @param bool $track_hash
     * @param bool $mail_id
     * @return string
     */
    static function generatePixelEmail($track_hash = false, $mail_id = false)
    {
        // checking hash
        if (empty($track_hash)) {
            $track_hash = self::HASH_MAIL_DEFAULT;
        }

        // checking email id
        $query = [];
        if (!empty($mail_id)) {
            $query = ['internal_mail_id' => $mail_id];
        }
        return self::generatePixel($track_hash, $query);
    }
}