<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SafetyData extends Model
{

    protected $table = 'safety_data';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_uid', 'encrypt_data', 'is_independentpass'];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['user_uid'];

    public function setIsIndependentpassAttribute($value)
    {
        $this->attributes['is_independentpass'] = (int) $value;
    }
    public function getIsIndependentpassAttribute($value)
    {
        return (bool) $value;
    }
}
