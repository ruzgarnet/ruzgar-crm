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
            'reference_code' => Generator::referenceCode()
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
}
