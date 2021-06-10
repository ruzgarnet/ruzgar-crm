<?php

return [
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
        'select' => 'Bayi Seçiniz'
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
        'types' => [
            1 => 'Önkayıt',
            2 => 'Onaylanmış'
        ],
        'select' => 'Müşteri Seç'
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
        'select' => 'Sözleşme Tipi Seç',
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
        'select' => 'Abonelik Seçiniz',
        'change_service' => 'Abone Hizmeti Değiştir',
        'types' => [
            1 => 'Aktif Abonelik',
            2 => 'Hazırlık Aşamasında'
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
        'select' => 'Ödeme Seçiniz',
        'status' => [
            1 => 'Sisteme Tanımlandı',
            2 => 'Ödeme Başarıyla Alındı',
            3 => 'Ödeme Alınırken Hata Oluştu'
        ],
        'select_type' => 'Ödeme Tipini Seçiniz',
        'types' => [
            1 => 'Nakit',
            2 => 'Havale/EFT',
            3 => 'Kredi/Banka Kartı (Online)',
            4 => 'Otomatik Ödeme'
        ]
    ],
    // Payment Fields End

    // Message Fields
    'message' => [
        'title' => 'Mesajlar',
        'singular' => 'Mesaj',
        'plural' => 'Mesajlar',
        'add' => 'Mesaj Ekle',
        'edit' => 'Mesaj Düzenle',
        'delete' => 'Mesaj Sil',
        'info' => 'Mesaj Bilgileri',
        'select' => 'Mesajı Seçiniz'
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
        'select' => 'Referansı Seçiniz',
        'status' => [
            1 => 'Referans işlemi tanımlandı',
            2 => 'Referans onaylandı, ücreti düşürüldü',
            3 => 'Faturalar düzenli ödenmediği için iptal edildi',
            4 => 'Abonelik(ler) iptal edildiği için işlem geçersiz',
            5 => 'Abonelik hizmeti değiştiği için işlem geçersiz',
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
    ]
    // Infrastructure Fields End
];
