<?php

namespace App\Models;

use App\Classes\Generator;
use App\Models\Attributes\ApprovedAtAttribute;
use App\Models\Attributes\EndDateAttribute;
use App\Models\Attributes\OptionValuesAttribute;
use App\Models\Attributes\PaymentAttribute;
use App\Models\Attributes\PriceAttribute;
use App\Models\Attributes\StartDateAttribute;
use App\Models\Mutators\SubscriptionPaymentMutator;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Subscription extends Model
{
    use HasFactory,
        PriceAttribute,
        PaymentAttribute,
        ApprovedAtAttribute,
        OptionValuesAttribute,
        SubscriptionPaymentMutator,
        StartDateAttribute,
        EndDateAttribute;

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
        'options' => 'array',
        'values' => 'array'
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
            $this->subscription_no = Generator::subscriptionNo();

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

    /**
     * Get value if is exists by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getValue(string $key, $default = false)
    {
        if (is_string($key) && isset($this->values[$key]) && !empty($this->values[$key])) {
            return $this->values[$key];
        }
        return $default;
    }
}
