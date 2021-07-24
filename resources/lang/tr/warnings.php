<?php

return [
    'delete' => 'Silinen veriler geri getirilemez ve bu veriyle bağlantılı verilerde silinir. Lütfen veriyi silmeden önce tekrar düşünün.',
    'approve' => [
        'customer' => 'Müşterinin kaydını onaylamak istediğinize emin misiniz?',
        'subscription' => 'Abonelik kaydını onaylamak istediğinize emin misiniz?',
        'message' => 'Müşteriye IBAN göndermek istiyor musunuz?'
    ],
    'approved' => [
        'subscription' => 'Onaylanmış aboneliği düzenleyemez veya silemezsiniz!'
    ],
    'unapproved' => [
        'subscription' => 'Onaylanmamış abonelikte ödeme işlemleri yapamazsınız!'
    ],
    'payment' => [
        'not_allowed_received_date' => 'Ödeme yapamazsınız.',
        'successful' => "Ödeme alındı.",
        'is_already_auto' => "Bu ödeme zaten otomatik ödeme olarak tanımlanmıştır.",
        'cancel_auto_payment' => 'Otomatik ödemeyi iptal etmek üzeresiniz. Bu işlemi gerçekleştirirseniz geri alamazsınız. Lütfen işlemi gerçekleştirmeden önce tekrar düşününüz.',
        'add_auto_payment_failure' => 'Otomatik ödeme tanımlanırken bir hata oluştu. Lütfen daha sonra tekrar deneyiniz.'
    ],
    'subscription' => [
        'not_approved' => 'Onaylanmamış abonelikte bu işlemi gerçekleştiremezsiniz!',
        'cant_change_same_service' => 'Değiştirmek istediğiniz hizmet, önceki hizmetle aynı!',
        'ended' => 'Taahhüt süresi bitmiş abonelikte bu işlemi gerçekleştiremezsiniz!',
        'already_changed' => 'Aboneliğin hizmeti zaten değiştirilmiş!',
        'changed' => 'Hizmeti değiştirilmiş abonelikte bu işlemi gerçekleştiremezsiniz!',
        'already_canceled' => 'Abonelik zaten iptal edilmiş!',
        'canceled' => 'İptal edilmiş abonelikte bu işlemi gerçekleştiremezsiniz!',
        'already_freezed' => 'Abonelik zaten dondurulmuş edilmiş!',
        'freezed' => 'Dondurulumuş abonelikte bu işlemi gerçekleştiremezsiniz!',
        'not_freezed' => 'Bu abonelik dondurulmamış!',
        'reset' => 'Abonelik bilgilerini sıfırlamak, bu abenolikle ilişkili tüm verileri silecektir. Bu gerçekleştirilirse geri alınamaz!',
        'cancel_auto_payment' => 'İptal etmek istediğinize emin misiniz ?',
        'campaign_description' => 'İlk :campaign_duration ay :campaign_price, sonraki aylarda :price.'
    ],
    'reference' => [
        'control_time' => 'Kontrol Edilebilir'
    ],
    'no_permission' => 'Bu işlemi gerçekleştirmek için yetkiniz bulunmamaktadır.'
];
