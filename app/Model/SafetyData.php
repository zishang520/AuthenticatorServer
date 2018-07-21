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
    protected $fillable = ['user_uid', 'encrypt_data'];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['user_uid'];
}
