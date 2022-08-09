<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'participants_studies';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'int';

     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    //protected $touches = ['participant'];

    /**
     * Get the participant that belongs to this participation.
     */
    public function participant()
    {
        return $this->belongsTo(Participant::class, 'participant_id', 'id');
    }

    /**
     * Get the study that belongs to this participation.
     */
    public function study()
    {
        return $this->belongsTo(Study::class, 'study_id', 'id');
    }
}
