<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Study extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'studies';

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
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'starts_at', 'ends_at', 'excludes'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = ['starts_at' => 'datetime:Y-m-d', 'ends_at' => 'datetime:Y-m-d'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['mailings_count'];

    /**
     * Get the participants for this study.
     */
    public function participants()
    {
        return $this->belongsToMany(Participant::class, 'participants_studies', 'study_id', 'participant_id', 'id', 'id');
    }

    /**
     * Get the mailings for this study.
     */
    public function mailings()
    {
        return $this->hasMany(Mailing::class, 'study_id', 'id');
    }

    /**
     * Get count of mailings.
     */
    public function getMailingsCountAttribute()
    {
        return $this->mailings->count();
    }
}
