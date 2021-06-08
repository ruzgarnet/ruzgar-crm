<?php

namespace App\Models;

use App\Classes\Generator;
use App\Models\Attributes\ApprovedAtAttribute;
use App\Models\Attributes\EndDateAttribute;
use App\Models\Attributes\OptionValuesAttribute;
use App\Models\Attributes\PaymentAttribute;
use App\Models\Attributes\PriceAttribute;
use App\Models\Attributes\StartDateAttribute;
use App\Models\Generators\SubscriptionChangeGenerator;
use App\Models\Generators\SubscriptionPaymentGenerator;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Subscription extends Model
{
    use HasFactory,
        PriceAttribute,
        PaymentAttribute,
        ApprovedAtAttribute,
        OptionValuesAttribute,
        SubscriptionPaymentGenerator,
        StartDateAttribute,
        EndDateAttribute,
        SubscriptionChangeGenerator;

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
     * Edit Subscription Price relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function editSubscriptionPrice()
    {
        return $this->hasMany(EditSubscriptionPrice::class);
    }

    /**
     * Get cancel information
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function canceledSubscription()
    {
        return $this->hasOne(CanceledSubscription::class);
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

    /**
     * Edit subscription's price
     *
     * @param array $data
     * @return void
     */
    public function edit_price(array $data)
    {
        $success = false;

        DB::beginTransaction();
        try {
            if (EditSubscriptionPrice::create($data)) {
                $this->price = $data['new_price'];
                $this->save();
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
     * Check row can editable
     *
     * If subs changed or expired returns true
     *
     * @return boolean
     */
    public function isEditable()
    {
        return !($this->isChanged() || $this->isCanceled() || ($this->end_date !== null && Carbon::parse($this->end_date)->isPast()));
    }

    /**
     * Check this row changed
     *
     * @return boolean
     */
    public function isChanged()
    {
        $count = DB::table('change_subscriptions')
            ->where('subscription_id', $this->id)
            ->count();

        return $count > 0 ? true : false;
    }

    /**
     * If row changed get new sub
     *
     * @return \App\Models\Subscription
     */
    public function getChanged()
    {
        $row = DB::table('change_subscriptions')
            ->where('subscription_id', $this->id)
            ->first();

        return self::find($row->changed_id) ?? false;
    }

    /**
     * Check subscriptions diasble status
     *
     * @return boolean
     */
    public function isCanceled()
    {
        $count = CanceledSubscription::where('subscription_id', $this->id)->count();

        return $count > 0 ? true : false;
    }

    /**
     * Change service
     *
     * Create new sub, generate new payments, delete old payments, insert changed info
     *
     * @param array $data
     * @return boolean
     */
    public function change_service(array $data)
    {
        $success = false;

        DB::beginTransaction();
        try {
            $sub = $this->getChangedSubscription($data);

            $this->addChangedRow($sub);

            $payments = $this->getChangedPayments($sub->id, $data['price']);
            $this->deleteChangedPayments();

            foreach ($payments as $payment) {
                Payment::insert($payment);
            }

            $this->end_date = Carbon::parse($data['date'])->format('Y-m-d');
            $this->save();

            DB::commit();

            $success = true;
        } catch (Exception $e) {
            DB::rollBack();

            $success = false;
        }

        return $success;
    }

    /**
     * Cancel subscription and delete next payments
     *
     * @param array $data
     * @return bool
     */
    public function cancel_subscription(array $data)
    {
        $success = false;

        DB::beginTransaction();
        try {
            CanceledSubscription::insert($data);

            $this->end_date = Carbon::now()->format('Y-m-d');
            $this->save();

            $dateAppend = $this->getOption('pre_payment') ? 0 : 1;

            Payment::where('subscription_id', $this->id)
                ->where('date', '>', Carbon::now()->addMonth($dateAppend)->lastOfMonth()->format('Y-m-d'))
                ->whereNull('paid_at')
                ->delete();

            DB::commit();

            $success = true;
        } catch (Exception $e) {
            DB::rollBack();

            $success = false;
        }

        return $success;
    }
}
