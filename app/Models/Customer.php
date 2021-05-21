<?php

namespace App\Models;

use App\Models\Attributes\FullNameAttribute;
use App\Models\Attributes\IdentificationSecretAttribute;
use App\Models\Attributes\PhoneAttribute;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class Customer extends Model
{
    use HasFactory, IdentificationSecretAttribute, FullNameAttribute, PhoneAttribute;

    /**
     * All fields fillable
     *
     * @var array
     */
    protected $guarded = [];

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
            'customer_no' => self::generateCustomerNo(),
            'reference_code' => self::generateReferenceCode()
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
     * Generate customer number
     *
     * @return string
     */
    private static function generateCustomerNo()
    {
        // Control for unique
        $pass = false;
        $rand = '';
        $rule = ['rand' => 'unique:customers,customer_no'];
        do {
            $rand = rand(1000, 9999) . rand(1000, 9999) . rand(100, 999);
            $input = ['rand' => $rand];
            $validator = Validator::make($input, $rule);
            if (!$validator->fails()) {
                $pass = true;
            } else {
                $pass = false;
            }
        } while ($pass !== true);

        return $rand;
    }

    /**
     * Generate reference code
     *
     * @return string
     */
    private static function generateReferenceCode()
    {
        // Control for unique
        $pass = false;
        $rand = '';
        $rule = ['rand' => 'unique:customers,reference_code'];
        do {
            $rand = 'R-' . (string)Str::of(Str::random(6))->upper();
            $input = ['rand' => $rand];
            $validator = Validator::make($input, $rule);
            if (!$validator->fails()) {
                $pass = true;
            } else {
                $pass = false;
            }
        } while ($pass !== true);

        return $rand;
    }

    /**
     * Customer info relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function info()
    {
        return $this->hasOne(CustomerInfo::class);
    }
}
