<?php namespace FrenchFrogs\Models\Db\Tracking;

use FrenchFrogs\Laravel\Database\Eloquent\Model;

class Log extends Model
{
    protected $primaryKey  = 'tracking_log_id';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tracking_log';

    public $uuid = true;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function tracking()

        return $this->belongsTo(Tracking::class);
    }
}