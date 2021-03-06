<?php

return [
    'title' => [
        'success' => 'Başarılı',
        'error' => 'Başarısız',
        'approve' => [
            'customer' => 'Müşteri Onayı',
            'subscription' => 'Abonelik Onayı'
        ]
    ],

    'insert' => [
        'success' => 'Veri başarıyla eklendi.',
        'error' => 'Veri eklenirken hata oluştu. Lütfen daha sonra tekrar deneyiniz.'
    ],

    'edit' => [
        'success' => 'Veri başarıyla düzenlendi.',
        'error' => 'Veri düzenlenirken hata oluştu. Lütfen daha sonra tekrar deneyiniz.'
    ],

    'delete' => [
        'success' => 'Veri başarıyla silindi.',
        'error' => 'Veri silinirken hata oluştu. Lütfen daha sonra tekrar deneyiniz.'
    ],

    'reference' => [
        'success' => 'Referans başarıyla tanımlandı.',
        'error' => 'Referans tanımlanırken hata oluştu. Lütfen daha sonra tekrar deneyiniz.'
    ],

    'approve' => [
        'customer' => [
            'success' => 'Müşteri kaydı başarıyla onaylandı.',
            'error' => 'Müşteri kaydı onaylanırken hata oluştu. Lütfen daha sonra tekrar deneyiniz.'
        ],
        'subscription' => [
            'success' => 'Abonelik kaydı başarıyla onaylandı.',
            'error' => 'Abonelik kaydı onaylanırken hata oluştu. Lütfen daha sonra tekrar deneyiniz.'
        ]
    ],

    'subscription' => [
        'change' => [
            'success' => 'Abonenin hizmeti başarıyla değiştirildi.',
            'error' => 'Abonenin hizmeti değiştirilirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.'
        ],
        'cancel' => [
            'success' => 'Abonelik başarıyla iptal edildi.',
            'error' => 'Abonenin iptal edilirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.'
        ],
        'freeze' => [
            'success' => 'Abonelik başarıyla donduruldu.',
            'error' => 'Abonenin dondurulurken bir hata oluştu. Lütfen daha sonra tekrar deneyin.'
        ],
        'renewal' => [
            'success' => 'Abonelik uzatma ücreti başarıyla tanımlandı.',
            'error' => 'Abonelik uzatma ücreti tanımlanırken bir hata oluştu. Lütfen daha sonra tekrar deneyin.'
        ],
        'end_commitment' => [
            'success' => 'Abonelik taahhütü başarıyla sonlandırıldı.',
            'error' => 'Abonelik taahhütü sonlandırılırken bir hata oluştu. Lütfen daha sonra tekrar deneyin.'
        ]
    ],

    'payment' => [
        'auto' => [
            'cancel' => [
                'success' => 'Otomatik ödeme başarıyla iptal edildi.',
                'error' => 'Otomatik ödeme iptal edilirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.'
            ]
        ]
    ],

    'system' => [
        'referenced' => 'Referans işlemi onaylandı. Ücreti :price TL\'ye göre uyarlandı. Bu mesaj sistem tarafından otomatik olarak tanımlanmıştır.',
        'freezing' => 'Dondurma işlemi başlatıldı. Bu mesaj sistem tarafından otomatik olarak tanımlanmıştır.',
        'price_freezed' => 'Dondurma işleminden dolayı ücreti düşürülmüştür. Bu mesaj sistem tarafından otomatik olarak tanımlanmıştır.',
        'freezing' => 'Dondurma işlemi kaldırıldı. Bu mesaj sistem tarafından otomatik olarak tanımlanmıştır.',
        'penalty' => ':price TL gecikme ücreti yansıtılmıştır. Bu mesaj sistem tarafından olarak tanımlanmıştır.',
        'auto_payment_discount' => ':price TL otomatik ödeme indirimi düşürülmüş. Bu mesaj sistem tarafından olarak tanımlanmıştır.',
        'auto_payment_penalty' => 'Otomatik ödeme iptalinden dolayı :price TL eklenmiştir. Bu mesaj sistem tarafından olarak tanımlanmıştır.'
    ]
];
