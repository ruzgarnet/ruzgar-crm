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

    public function approve_sub()
    {
        DB::beginTransaction();

        try {
            $this->approved_at = DB::raw('current_timestamp()');

            $data = [
                "subscription_id" => $this->id,
                "date" => date('Y-m-15', strtotime("+1 month")),
                "price" => $this->price
            ];
            if (isset($this->options["pre_payment"]) && $this->options["pre_payment"] == 1) {
                $data["date"] = date('Y-m-15');
                $data["paid_at"] = DB::raw('current_timestamp()');
                $data["type"] = 1;
                $data["status"] = 2;
            }

            $this->save();
            Payment::insert($data);

            DB::commit();
            $success = true;
        } catch (Exception $e) {
            DB::rollBack();
            $success = false;
        }

        return $success;
    }
}
