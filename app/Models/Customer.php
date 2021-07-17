<?php

namespace App\Models;

use App\Classes\Generator;
use App\Models\Attributes\FullNameAttribute;
use App\Models\Attributes\IdentificationSecretAttribute;
use App\Models\Attributes\PersonSelectPrintAttribute;
use App\Models\Attributes\PhoneAttribute;
use App\Models\Helpers\FieldHelper;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    use HasFactory,
        IdentificationSecretAttribute,
        FullNameAttribute,
        PhoneAttribute,
        PersonSelectPrintAttribute,
        FieldHelper;

    /**
     * All fields fillable
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Customer info relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function info()
    {
        return $this->hasOne(CustomerInfo::class);
    }

    /**
     * Staff relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function staffs()
    {
        return $this->belongsToMany(Staff::class);
    }

    /**
     * Fault Record relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function faultRecords()
    {
        return $this->hasMany(FaultRecord::class);
    }

    /**
     * Get staff
     *
     * @return \App\Models\Staff
     */
    public function getStaffAttribute()
    {
        if (!$this->staffs()->count()) {

            $staff_id = DB::table('staff')->select('id')->whereRaw('id NOT IN (SELECT staff_id FROM customer_staff)')->whereRaw("id IN (SELECT staff_id FROM users WHERE role_id = 3)")->limit(1)->first()->id ?? null;

            if ($staff_id == null) {
                $staff_id = DB::table('customer_staff')
                ->selectRaw('COUNT(*) AS count, staff_id')->groupBy('staff_id')->orderByRaw('COUNT(*)')->first()->staff_id;
            }

            DB::table('customer_staff')->insert([
                'customer_id' => $this->id,
                'staff_id' => $staff_id
            ]);
        }

        return $this->staffs()->first();
    }

    /**
     * Customer info relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function customerInfo()
    {
        return $this->hasOne(CustomerInfo::class);
    }

    /**
     * Subscription relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Add customer's main and info data
     *
     * @param array $data
     * @return boolean
     */
    public static function add_data(array $data)
    {
        $main = [
            'identification_number' => $data['identification_number'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'telephone' => $data['telephone'],
            'email' => $data['email'],
            'customer_no' => Generator::customerNo(),
            'reference_code' => Generator::referenceCode(),
            'type' => 2
        ];

        $info = [
            'gender' => $data['gender'],
            'secondary_telephone' => $data['secondary_telephone'],
            'birthday' => $data['birthday'],
            'city_id' => $data['city_id'],
            'district_id' => $data['district_id'],
            'address' => $data['address']
        ];

        $success = false;

        DB::beginTransaction();

        try {
            $customer = self::create($main);
            CustomerInfo::insert(
                $info + ['customer_id' => $customer->id]
            );
            DB::commit();
            $success = true;
        } catch (Exception $e) {
            DB::rollBack();
            $success = false;
        }

        return $success;
    }

    /**
     * Edit customer's main and info data
     *
     * @param array $data
     * @return boolean
     */
    public function update_data(array $data)
    {
        $main = [
            'identification_number' => $data['identification_number'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'telephone' => $data['telephone'],
            'email' => $data['email']
        ];

        $info = [
            'gender' => $data['gender'],
            'secondary_telephone' => $data['secondary_telephone'],
            'birthday' => $data['birthday'],
            'city_id' => $data['city_id'],
            'district_id' => $data['district_id'],
            'address' => $data['address']
        ];

        $success = false;

        DB::beginTransaction();

        try {
            $this->update($main);
            $this->info->update($info);
            DB::commit();
            $success = true;
        } catch (Exception $e) {
            DB::rollBack();
            $success = false;
        }

        return $success;
    }

    /**
     * Approve customer
     *
     * @return boolean
     */
    public function approve()
    {
        DB::beginTransaction();

        try {
            $this->type = 2;
            $this->save();

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}
