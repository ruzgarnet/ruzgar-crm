<?php

namespace App\Models;

use App\Models\Attributes\ApprovedAtAttribute;
use App\Models\Attributes\OptionValuesAttribute;
use App\Models\Attributes\PaymentAttribute;
use App\Models\Attributes\PriceAttribute;
use App\Models\Mutators\SubscriptionPaymentMutator;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Subscription extends Model
{
    use HasFactory, PriceAttribute, PaymentAttribute, ApprovedAtAttribute, OptionValuesAttribute, SubscriptionPaymentMutator;

    /**
     * All fields fillable
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'array'
    ];

    /**
     * Staff relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Customer relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Service relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Payment relationship with ordered query
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        return $this->hasMany(Payment::class)->orderByDesc('date');
    }

    /**
     * Approve and add first payment(s)
     *
     * @return boolean
     */
    public function approve_sub()
    {
        DB::beginTransaction();

        try {
            $this->approved_at = DB::raw('current_timestamp()');
            $this->subscription_no = $this->generateSubscriptionNo();

            $payments = $this->generatePayments();

            $this->save();

            foreach ($payments as $payment) {
                Payment::insert($payment);
            }

            DB::commit();
            $success = true;
        } catch (Exception $e) {
            DB::rollBack();
            $success = false;
        }

        return $success;
    }

    /**
     * Unapprove and delete payments
     *
     * @return boolean
     */
    public function unapprove_sub()
    {
        DB::beginTransaction();

        try {
            $this->approved_at = null;
            $this->save();

            Payment::where('subscription_id', $this->id)->delete();

            DB::commit();
            $success = true;
        } catch (Exception $e) {
            $success = false;
            DB::rollBack();
        }

        return $success;
    }

    /**
     * Generate subscription number
     *
     * @return string
     */
    private static function generateSubscriptionNo()
    {
        // Control for unique
        $pass = false;
        $rand = '';
        $rule = ['rand' => 'unique:subscriptions,subscription_no'];
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
     * Get option if is exists by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getOption(string $key, $default = false)
    {
        if (is_string($key) && isset($this->options[$key]) && !empty($this->options[$key])) {
            return $this->options[$key];
        }
        return $default;
    }
}
