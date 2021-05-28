<?php

namespace App\Models;

use App\Models\Attributes\PriceAttribute;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Subscription extends Model
{
    use HasFactory, PriceAttribute;

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
     * Approve and add first payment(s)
     *
     * @return boolean
     */
    public function approve_sub()
    {
        DB::beginTransaction();

        try {
            $this->approved_at = DB::raw('current_timestamp()');

            $data = [
                'subscription_id' => $this->id,
                'date' => date('Y-m-15', strtotime('+1 month')),
                'price' => $this->price
            ];

            if (isset($this->options['pre_payment']) && $this->options['pre_payment'] == true) {
                $data = [
                    'subscription_id' => $this->id,
                    'date' => date('Y-m-15'),
                    'price' => $this->price,
                    'paid_at' => DB::raw('current_timestamp()'),
                    'type' => 1,
                    'status' => 2
                ];
            }

            $this->save();
            Payment::insert($data);

            // NOTE Multiple insert not worked, because adding columns not same
            if (isset($this->options['pre_payment']) && $this->options['pre_payment'] == true) {
                Payment::insert([
                    'subscription_id' => $this->id,
                    'date' => date('Y-m-15', strtotime('+1 month')),
                    'price' => $this->price
                ]);
            }

            DB::commit();
            $success = true;
        } catch (Exception $e) {
            DB::rollBack();
            $success = false;
        }

        return $success;
    }
}
