<?php

return [
    // Main Fields
    'main' => [
        'title' => 'Ana İşlemler',
        'report' => 'Rapor'
    ],
    // Main Fields End

    // City Fields
    'city' => [
        'title' => 'İl',
        'singular' => 'İl',
        'plural' => 'İller',
        'select' => 'İl Seçiniz'
    ],
    // City Fields End

    // District Fields
    'district' => [
        'title' => 'İlçe',
        'singular' => 'İlçe',
        'plural' => 'İlçeler',
        'select' => 'İlçe Seçiniz'
    ],
    // District Fields End

    // Dealer Fields
    'dealer' => [
        'title' => 'Bayiler',
        'singular' => 'Bayi',
        'plural' => 'Bayiler',
        'add' => 'Bayi Ekle',
        'edit' => 'Bayi Düzenle',
        'delete' => 'Bayi Sil',
        'info' => 'Bayi Bilgileri',
        'select' => 'Bayi Seçiniz',
        'list' => 'Bayileri Listele'
    ],
    // Dealer Fields End

    // Staff Fields
    'staff' => [
        'title' => 'Personeller',
        'singular' => 'Personel',
        'plural' => 'Personeller',
        'add' => 'Personel Ekle',
        'edit' => 'Personel Düzenle',
        'delete' => 'Personel Sil',
        'info' => 'Personel Bilgileri',
        'select' => 'Personel Seç',
        'list' => 'Personelleri Listele'
    ],
    // Staff Fields End

    // User Fields
    'user' => [
        'title' => 'Kullanıcılar',
        'singular' => 'Kullanıcı',
        'plural' => 'Kullanıcılar',
        'add' => 'Kullanıcı Ekle',
        'edit' => 'Kullanıcı Düzenle',
        'delete' => 'Kullanıcı Sil',
        'info' => 'Kullanıcı Bilgileri',
        'list' => 'Kullanıcıları Listele'
    ],
    // User Fields End

    // Customer Fields
    'customer' => [
        'title' => 'Müşteriler',
        'singular' => 'Müşteri',
        'plural' => 'Müşteriler',
        'add' => 'Müşteri Ekle',
        'edit' => 'Müşteri Düzenle',
        'delete' => 'Müşteri Sil',
        'info' => 'Müşteri Bilgileri',
        'show' => 'Müşteri Detayları',
        'list' => 'Müşterileri Listele',
        'types' => [
            1 => 'Önkayıt',
            2 => 'Onaylanmış',
            3 => 'Eski Sistemden Aktarılan'
        ],
        'select' => 'Müşteri Seç',
        'approve' => 'Müşteri Onayla'
    ],
    // Customer Fields End

    // Contract Type Fields
    'contract_type' => [
        'title' => 'Sözleşme Tipleri',
        'singular' => 'Sözleşme Tipi',
        'plural' => 'Sözleşme Tipleri',
        'add' => 'Sözleşme Tipi Ekle',
        'edit' => 'Sözleşme Tipi Düzenle',
        'delete' => 'Sözleşme Tipi Sil',
        'info' => 'Sözleşme Tipi Bilgileri',
        'list' => 'Sözleşme Tiplerini Listele',
        'select' => 'Sözleşme Tipi Seç'
    ],
    // Contract Type Fields End

    // Category Fields
    'category' => [
        'title' => 'Kategoriler',
        'singular' => 'Kategori',
        'plural' => 'Kategoriler',
        'add' => 'Kategori Ekle',
        'edit' => 'Kategori Düzenle',
        'delete' => 'Kategori Sil',
        'info' => 'Kategori Bilgileri',
        'list' => 'Kategorileri Listele',
        'types' => [
            1 => 'Ürün',
            2 => 'Hizmet'
        ],
        'status' => [
            1 => 'Aktif',
            2 => 'Kapalı'
        ],
        'select_type' => 'Kategori Tipini Seç',
        'select' => 'Kategori Seç'
    ],
    // Category Fields End

    // Product Fields
    'product' => [
        'title' => 'Ürünler',
        'singular' => 'Ürün',
        'plural' => 'Ürünler',
        'add' => 'Ürün Ekle',
        'edit' => 'Ürün Düzenle',
        'delete' => 'Ürün Sil',
        'info' => 'Ürün Bilgileri',
        'list' => 'Ürünleri Listele',
        'select' => 'Ürün Seçiniz',
        'status' => [
            1 => 'Aktif',
            2 => 'Kapalı'
        ]
    ],
    // Product Fields End

    // Service Fields
    'service' => [
        'title' => 'Hizmetler',
        'singular' => 'Hizmet',
        'plural' => 'Hizmetler',
        'add' => 'Hizmet Ekle',
        'edit' => 'Hizmet Düzenle',
        'delete' => 'Hizmet Sil',
        'info' => 'Hizmet Bilgileri',
        'list' => 'Hizmetleri Listele',
        'select' => 'Hizmet Seçiniz',
        'status' => [
            1 => 'Aktif',
            2 => 'Kapalı'
        ]
    ],
    // Service Fields End

    // Subscription Fields
    'subscription' => [
        'title' => 'Abonelikler',
        'singular' => 'Abonelik',
        'plural' => 'Abonelikler',
        'add' => 'Abonelik Ekle',
        'edit' => 'Abonelik Düzenle',
        'delete' => 'Abonelik Sil',
        'info' => 'Abonelik Bilgileri',
        'list' => 'Abonelikleri Listele',
        'select' => 'Abonelik Seçiniz',
        'change_service' => 'Tarife Değiştirme',
        'types' => [
            1 => 'Aktif Abonelik',
            2 => 'Hazırlık Aşamasında'
        ],
        'status' => [
            0 => 'Hazırlık Aşamasında',
            1 => 'Aktif Abonelik',
            2 => 'Tarife Değiştirilmiş',
            3 => 'İptal Edilmiş',
            4 => 'Dondurulmuş'
        ],
        'create_payment' => 'Fatura Oluştur',
        'approve' => 'Abonelik Onayla',
        'unapprove' => 'Abonelik Sıfırla',
        'payments' => 'Ödemeleri Listele',
        'price' => 'Abonelik Ücreti Düzenle',
        'change' => 'Tarife Değiştir',
        'cancel' => 'Abonelik İptali',
        'contract' => 'Sözleşme Görüntüle',
        'freeze' => 'Abonelik Dondurma',
        'unfreeze' => 'Abonelik Aktif Etme',
        'cancel_auto_payment' => 'Otomatik ödemeyi iptal et',
        'payment' => [
            'add' => 'Fatura Ekle',
            'cancel' => 'Fatura İptal Et',
            'delete' => 'Fatura Sil'
        ]
    ],
    // Subscription Fields End

    // Payment Fields
    'payment' => [
        'title' => 'Ödemeler',
        'singular' => 'Ödeme',
        'plural' => 'Ödemeler',
        'add' => 'Ödeme Ekle',
        'edit' => 'Ödeme Düzenle',
        'delete' => 'Ödeme Sil',
        'info' => 'Ödeme Bilgileri',
        'list' => 'Ödemeleri Listele',
        'select' => 'Ödeme Seçiniz',
        'status' => [
            1 => 'Ödenmemiş',
            2 => 'Ödenmiş'
        ],
        'select_type' => 'Ödeme Tipini Seçiniz',
        'types' => [
            0 => 'Ödenmemiş',
            1 => 'Nakit',
            2 => 'Kredi/Banka Kartı (Pos)',
            3 => 'Havale/EFT',
            4 => 'Kredi/Banka Kartı (Online)',
            5 => 'Otomatik Ödeme',
            6 => 'Nakit (Ön Ödeme)'
        ],
        'received' => 'Ödeme Al',
        'price' => 'Ücret Düzenle',
        'penalty' => 'Gecikmiş Ödemeler',
        'monthly' => 'Aylık Ödemeler',
        'penalty_status' => [
            1 => 'Ödenmemiş',
            2 => 'Ödenmiş',
        ],
    ],
    // Payment Fields End

    // Customer Application Fields
    'customer_application' => [
        'title' => 'Başvurular',
        'singular' => 'Başvuru',
        'plural' => 'Başvurular',
        'add' => 'Başvuru Ekle',
        'edit' => 'Başvuru Düzenle',
        'delete' => 'Başvuru Sil',
        'info' => 'Başvuru Bilgileri',
        'list' => 'Başvuruları Listele',
        'select' => 'Başvuru Seçiniz',
        'status' => [
            1 => 'Sisteme Tanımlandı',
            2 => 'Olumlu',
            3 => 'Olumsuz'
        ],
        'select_type' => 'Başvuru Tipini Seçiniz',
        'types' => [
            1 => 'İptal',
            2 => 'Bilgi Alma',
            3 => 'Tarife Yükseltme',
            4 => 'Dondurma',
            5 => 'İstek/Öneri/Şikayet'
        ],

    ],
    // Customer Application End

    // Customer Application Type Fields
    'customer_application_type' => [
        'title' => 'Başvuru Tipleri',
        'singular' => 'Başvuru Tipi',
        'plural' => 'Başvuru Tipleri',
        'add' => 'Başvuru Tipi Ekle',
        'edit' => 'Başvuru Tipini Düzenle',
        'delete' => 'Başvuru Tipini Sil',
        'info' => 'Başvuru Tipi Bilgileri',
        'list' => 'Başvuru Tiplerini Listele',
        'select' => 'Başvuru Tipini Seçiniz',
        'status' => [
            1 => 'Aktif',
            2 => 'Kapalı'
        ],
        'select_type' => 'Başvuru Tipini Seçiniz'
    ],
    // Customer Application Type End

    // Message Fields
    'message' => [
        'title' => 'Mesajlar',
        'singular' => 'Mesaj',
        'plural' => 'Mesajlar',
        'add' => 'Mesaj Ekle',
        'edit' => 'Mesaj Düzenle',
        'delete' => 'Mesaj Sil',
        'info' => 'Mesaj Bilgileri',
        'list' => 'Mesajları Listele',
        'select' => 'Mesajı Seçiniz',
        'selects' => [
            1 => 'Müşteri',
            2 => 'Kategori',
            3 => 'Tüm Aboneler',
            4 => 'Ödemeyenler',
            5 => 'Gecikme Ücreti Yansıyacaklar'
        ],
        'alt_title' => 'Mesaj Tipleri',
        'send_sms' => 'Mesaj Gönder',
        'send_sms_to_number' => 'Mesaj Gönder',
        'send' => 'Mesaj Gönder'
    ],
    // Message Fields End

    // Reference Fields
    'reference' => [
        'title' => 'Referanslar',
        'singular' => 'Referans',
        'plural' => 'Referanslar',
        'add' => 'Referans Ekle',
        'edit' => 'Referans Düzenle',
        'delete' => 'Referans Sil',
        'info' => 'Referans Bilgileri',
        'list' => 'Referansları Listele',
        'select' => 'Referansı Seçiniz',
        'status' => [
            'titles' => [
                1 => 'Tanımlandı',
                2 => 'Onaylandı',
                3 => 'İptal Edildi',
                4 => 'Şartlara Uygun Değil',
                5 => 'Abonelik İptal Edilmiş',
            ],
            'descriptions' => [
                1 => 'Referans işlemi tanımlandı',
                2 => 'Referans onaylandı, ücreti düşürüldü',
                3 => 'Faturalar düzenli ödenmediği için iptal edildi',
                4 => 'Şartları karşılamayan abonelikten dolayı iptal edildi',
                5 => 'Abonelik iptal edildiği için işlem geçersiz'
            ]
        ]
    ],
    // Reference Fields End

    // Infrastructure Fields
    'infrastructure' => [
        'title' => 'Altyapı Sorgula',
        'townships' => 'Bucak Seçiniz',
        'villages' => 'Kasaba/Köy Seçiniz',
        'neighborhoods' => 'Mahalle Seçiniz',
        'streets' => 'Cadde/Sokak/Bulvar/Meydan Seçiniz',
        'buildings' => 'Bina No/Adı Seçiniz',
        'doors' => ' Kapı No Seçiniz'
    ],
    // Infrastructure Fields End

    // Fault Fields
    'fault' => [
        // Fault Type Fields
        'type' => [
            'title' => 'Arıza Tipleri',
            'singular' => 'Arıza Tipi',
            'plural' => 'Arıza Tipleri',
            'add' => 'Arıza Tipi Ekle',
            'edit' => 'Arıza Tipi Düzenle',
            'delete' => 'Arıza Tipi Sil',
            'info' => 'Arıza Tipi Bilgileri',
            'list' => 'Arıza Tiplerini Listele',
            'status' => [
                1 => 'Aktif',
                2 => 'Kapalı'
            ],
            'select_type' => 'Arıza Tipini Seç',
            'select' => 'Arıza Tipi Seç'
        ],
        // Fault Type Fields End

        // Fault Record Fields
        'record' => [
            'title' => 'Arıza Kayıtları',
            'singular' => 'Arıza Kaydı',
            'plural' => 'Arıza Kayıtları',
            'add' => 'Arıza Kaydı Ekle',
            'edit' => 'Arıza Kaydı Düzenle',
            'delete' => 'Arıza Kaydı Sil',
            'info' => 'Arıza Kaydı Bilgileri',
            'list' => 'Arıza Kayıtlarını Listele',
            'status' => [
                1 => 'Sisteme Tanımlandı',
                2 => 'Müşteri Temsilcisine Aktarıldı',
                3 => 'Teknik Ekibe Aktarıldı',
                4 => 'Teknik Ekip Müdahale Ediyor',
                5 => 'Kalite Kontrol Ekibi Tarafından Onaylandı',
                6 => 'Çözülemedi'
            ],
            'select_type' => 'Arıza Tipini Seç',
            'select' => 'Arıza Kaydı Seç'
        ],
        // Fault Record Fields End
    ],
    // Fault Fields End

    'moka_log' => [
        'types' => [
            1 => 'Online Satış 3D',
            2 => 'Online Satış Cevabı',
            3 => 'Otomatik Ödeme Tanımlama',
            4 => 'Ödeme Planı Ekleme',
            5 => 'Ödeme Planı Cevabı',
            6 => 'Online Satış 3D Hata'
        ],
        'statuses' => [
            1 => 'Hatalı',
            2 => 'Hatasız'
        ]
    ],

    // Role Fields
    'role' => [
        'title' => 'Yetkiler',
        'singular' => 'Yetki',
        'plural' => 'Yetkiler',
        'add' => 'Yetki Ekle',
        'edit' => 'Yetki Düzenle',
        'delete' => 'Yetki Sil',
        'info' => 'Yetki Bilgileri',
        'list' => 'Yetikleri Listele',
        'select' => 'Yetki Seç',
    ],
    // Role Fields End

    // Request Fields
    'request' => [
        'message' => [
            'status'=>[
                1 => 'Aktif',
                2 => 'Beklemede',
                3 => 'Tamamlandı'
            ],
        'title' => 'Talepler',
        'singular' => 'Talep',
        'plural' => 'Talepler',
        'add' => 'Talep Ekle',
        'edit' => 'Talep Düzenle',
        'delete' => 'talep Sil',
        'info' => 'Talep Bilgileri',
        'list' => 'Talep Listele',
        'select' => 'Talep Seçiniz',
        'role' => 'Birim Seçiniz',

        ],
    ],
    // Message Fields End
];
