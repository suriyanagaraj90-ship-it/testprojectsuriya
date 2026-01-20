<?php



namespace App;



use Illuminate\Notifications\Notifiable;

use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Spatie\Permission\Traits\HasRoles;



class Setting extends Authenticatable

{

    use Notifiable, HasRoles;

 

    /**

     * The attributes that are mass assignable.

     *

     * @var array

     */

    protected $fillable = [

        'user_id', 'invoice_prefix','enquiry_prefix', 'staff_prefix','reg_amt','invoice_terms_conditions', 'created_by','plan_expiry', 'updated_by','fund_amt','commission_amt','game_amt','level1_amt','level2_amt','level3_amt','level4_amt','level5_amt','gst','address','state','city','zipcode', 'email','phone','footer_content','opening_hours', 'facebook_link', 'twitter_link', 'gplus_link', 'pintrest_link', 'youtube_link','offer','ref_count'

    ];

}

