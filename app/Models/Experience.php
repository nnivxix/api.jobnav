<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use App\Models\Concerns\HasUrlAsset;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Experience extends Model
{
    use HasFactory;
    use HasUrlAsset;

    protected $casts = [
        'started'    => 'date',
        'ended'      => 'date',
    ];
    protected $guarded = [
        'id',
    ];

    /** 
     * Accessors
     */

    public function stillWorking(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (empty($this->ended)) {
                    return true;
                }
                return false;
            }
        );
    }

    public function endedStatus(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (empty($this->ended)) {
                    return "Present";
                }
                return $this->ended;
            }
        );
    }

    public function timeSpan(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (empty($this->ended)) {
                    return now()->diffInMonths($this->started) + 1;
                }
                return $this->ended->diffInMonths($this->started) + 1;
            }
        );
    }
}
