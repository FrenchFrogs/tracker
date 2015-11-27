<?php namespace Models\Db\Tracking;

use FrenchFrogs\Laravel\Database\Eloquent\Model;

class Tracking extends Model
{
    protected $primaryKey  = 'tracking_id';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tracking';

    public $uuid = true;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function logs()
    {
        return $this->hasMany(TrackingLog::class, 'tracking_id', 'tracking_id');
    }

    /**
     * Generate a hash for tracking
     *
     * @return string
     */
    public static function generateHash()
    {
        // generate hash which not exists already
        while(true){
            $hash = str_random(10);
            if(!self::where('tracking_hash', '=', $hash)->first()){
                return $hash;
            }
        }
    }

    public function isActive()
    {
        return (bool) ($this->is_active) ? true : false;
    }
}