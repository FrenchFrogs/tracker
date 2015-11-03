<?php namespace Models\Db\Tracking;

use Illuminate\Database\Eloquent\Model;

class TrackingLog extends Model
{
    protected $primaryKey  = 'tracking_log_id';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tracking_log';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function tracking()
    {
        return $this->belongsTo(Tracking::class);
    }
}