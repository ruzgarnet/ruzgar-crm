<?php

namespace App\Models;

use App\Classes\Generator;
use App\Models\Attributes\ApprovedAtAttribute;
use App\Models\Attributes\EndDateAttribute;
use App\Models\Attributes\OptionValuesAttribute;
use App\Models\Attributes\PaymentAttribute;
use App\Models\Attributes\PriceAttribute;
use App\Models\Attributes\StartDateAttribute;
use App\Models\Attributes\SubscriptionAddressAttribute;
use App\Models\Attributes\SubscriptionContractPrintAttribute;
use App\Models\Attributes\SubscriptionSelectPrintAttribute;
use App\Models\Attributes\SubscriptionServicePrintAttribute;
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
        SubscriptionAddressAttribute,
        SubscriptionSelectPrintAttribute,
        SubscriptionContractPrintAttribute,
        SubscriptionServicePrintAttribute;

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
     * Edit Subscription Price Relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function priceEdits()
    {
        return $this->hasMany(SubscriptionPriceEdit::class);
    }

    /**
     * Canceled Subscription Relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cancellation()
    {
        return $this->hasOne(SubscriptionCancellation::class);
    }

    /**
     * Changed Subscription Relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function changed()
    {
        return $this->hasOne(SubscriptionChange::class);
    }

    /**
     * Freeze Subscription Relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function freeze()
    {
        return $this->hasMany(SubscriptionFreeze::class);
    }

    /**
     * Sale relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sales()
    {
        return $this->hasOne(MokaSale::class);
    }

    /**
     * Returns only active subscriptions
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getActive()
    {
        return self::where('status', 1)->get();
    }

    /**
     * Get subscription status
     *
     * 0 => Unapproved  - Onaylanmamış
     * 1 => Approved    - Onaylandı
     * 2 => Changed     - Değiştirilmiş
     * 3 => Canceled    - İptal Edilmiş
     * 4 => Freezed     - Donmuş
     *
     * @param boolean $implode
     * @return array|string
     */
    public static function getStatus($implode = false)
    {
        $data = [1, 2, 3, 4];
        return $implode ? implode(',', $data) : $data;
    }

    /**
     * Returns current payment
     *
     * @return \App\Models\Payment
     */
    public function currentPayment()
    {
        return Payment::where('subscription_id', $this->id)
            ->where('date', date('Y-m-15'))
            ->first();
    }

    /**
     * Returns next payment
     *
     * @return \App\Models\Payment
     */
    public function nextPayment()
    {
        return Payment::where('subscription_id', $this->id)
            ->whereNull('paid_at')
            ->orderBy('date', 'ASC')
            ->first();
    }

    /**
     * Check auto payment information
     *
     * @return boolean
     */
    public function is_auto()
    {
        return $this->sales()->whereNull("disabled_at")->count() > 0 ? true : false;
    }

    /**
     * Get auto payment information
     *
     * @return object|null
     */
    public function get_auto()
    {
        return $this->is_auto() ? $this->sales()->whereNull("disabled_at")->first() : null;
    }

    /**
     * Approve and add first payment(s)
     *
     * @return boolean
     */
    public function approve_subscription()
    {
        DB::beginTransaction();
        try {
            $this->approved_at = DB::raw('current_timestamp()');
            $this->status = 1;
            $this->subscription_no = Generator::subscriptionNo();

            $payments = $this->generatePayments();

            $this->save();

            // Set the first price after 25
            // 25'ten sonra aboneliği eklenirse ilk ay ücretini yarıya düş
            foreach ($payments as $index => $payment) {
                if (date('d') >= 25 && $index == 0) {
                    $payment["price"] /= 2;
                }
                Payment::insert($payment);
            }

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Unapprove and delete payments
     *
     * @return boolean
     */
    public function unapprove_subscription()
    {
        DB::beginTransaction();
        try {
            $this->approved_at = null;
            $this->status = 0;
            $this->save();

            $this->payments()->delete();
            $this->priceEdits()->delete();
            $this->cancellation()->delete();
            $this->changed()->delete();

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
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
            if ($key == "modem_model") {
                $data = json_decode(setting("service.modems"), true);
                foreach ($data as $item) {
                    if ($item["value"] == $this->options[$key]) {
                        return $item["title"];
                    }
                }
            }
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
     * Check row can editable
     *
     * If subs changed or expired returns true
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->approved_at != null &&
            !($this->isChanged() ||
                $this->isCanceled() ||
                ($this->end_date != null && Carbon::parse($this->end_date)->isPast()));
    }

    /**
     * Check this row changed
     *
     * @return boolean
     */
    public function isChanged()
    {
        return $this->status == 2 ? true : false;
    }

    /**
     * Check this row ended
     *
     * @return boolean
     */
    public function isEnded()
    {
        return $this->end_date != null && Carbon::parse($this->end_date)->isPast();
    }

    /**
     * Check this row changed
     *
     * @return boolean
     */
    public function isFreezed()
    {
        return $this->status == 4 ? true : false;
    }

    /**
     * Check subscriptions disable status
     *
     * @return boolean
     */
    public function isCanceled()
    {
        return $this->status == 3 ? true : false;
    }

    /**
     * If row changed get new sub
     *
     * @return \App\Models\Subscription
     */
    public function getChanged()
    {
        $row = DB::table('subscription_changes')
            ->where('subscription_id', $this->id)
            ->first();

        return $row ? self::find($row->changed_id) : false;
    }
}
