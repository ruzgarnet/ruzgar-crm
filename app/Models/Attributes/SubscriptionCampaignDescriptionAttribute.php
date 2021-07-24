<?php

namespace App\Models\Attributes;

/**
 * campaign_description attribute for subscription
 */
trait SubscriptionCampaignDescriptionAttribute
{
    /**
     * Return description for campaign information
     *
     * @return string|null
     */
    public function getCampaignDescriptionAttribute()
    {
        $campaign_duration = $this->getValue('duration');
        $campaign_price = $this->getValue('campaign_price');
        $price = $this->price;

        if ($campaign_duration && $campaign_price) {
            return trans('warnings.subscription.campaign_description', [
                'campaign_duration' => $campaign_duration,
                'campaign_price' => print_money($campaign_price),
                'price' => print_money($price)
            ]);
        }
        return null;
    }
}
