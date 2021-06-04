<?php

return [
    // Global Attributes
    'send' => 'Gönder',
    'actions' => 'İşlemler',
    'none' => 'Yok',
    'details' => 'Detaylar',
    'options' => 'Seçenekler',
    'option' => 'Seçenek',
    'define' => 'Tanımla',
    // Global Attributes End

    // Identity Attributes
    'name' => 'İsim',
    'first_name' => 'Ad',
    'last_name' => 'Soyad',
    'name_surname' => 'Ad Soyad',
    'identification_number' => 'Kimlik Numarası',
    'gender' => 'Cinsiyet',
    'genders' => [
        1 => 'Erkek',
        2 => 'Kadın'
    ],
    'male' => 'Erkek',
    'female' => 'Kadın',
    'telephone' => 'Telefon Numarası',
    'secondary_telephone' => 'İkincil Telefon Numarası',
    'birthday' => 'Doğum Tarihi',
    'city' => 'İl',
    'district' => 'İlçe',
    'city_district' => 'İl/İlçe',
    'address' => 'Adres',
    // Identity Attributes End

    // Name Attributes
    'title' => 'Başlık',
    'price' => 'Tutar',
    'message' => 'Mesaj',
    'category' => 'Kategori',
    'model' => 'Model',
    'content' => 'İçerik',
    'options' => 'Seçenekler',
    'data_type' => 'Kayıt Tipi',
    'staff' => 'Personel',
    'service' => 'Hizmet',
    'customer' => 'Müşteri',
    'customer_no' => 'Müşteri Numarası',
    'reference_code' => 'Referans Kodu',
    'description' => 'Açıklama',
    // Name Attributes End

    // Auth|User Attributes
    'username' => 'Kullanıcı Adı',
    'email' => 'E-Posta Adresi',
    'password' => 'Şifre',
    // Auth|User Attributes End

    // Date Attributes
    'started_at' => 'Başlama Tarihi',
    'ended_at' => 'Bitiş Tarihi',
    'released_at' => 'Çıkış Tarihi',
    'start_date' => 'Başlangıç Tarihi',
    'end_date' => 'Bitiş Tarihi',
    'approve_date' => 'Onay Tarihi',
    'date' => 'Tarih',
    'paid_date' => 'Ödeme Tarihi',
    'save_date' => 'Kayıt Tarihi',
    'subscription_date' => 'Abonelik Tarihi',
    // Date Attributes End

    // Meta Attributes
    'slug' => 'Slug',
    'meta' => [
        'title' => 'Meta Başlığı',
        'keywords' => 'Meta Anahtar Kelimeleri',
        'description' => 'Meta Açıklaması'
    ],
    'key' => 'Anahtar',
    'view' => 'View',
    // Meta Attributes End

    // Dealer Attributes
    'tax_number' => 'Vergi Numarası',
    'dealer' => 'Bayi',
    'worker' => 'Çalışan',
    'worker_count' => 'Çalışan Sayısı',
    // Dealer Attributes End

    // Category|Product|Service Attributes
    'parent_category' => 'Üst Kategori',
    'contract_type' => 'Sözleşme Tipi',
    'category_type' => 'Kategori Tipi',
    // Category|Product|Service Attributes

    // Subscription Attributes
    'bbk_code' => 'BBK Kodu',
    'payment_type' => 'Ödeme Şekli',
    'commitment_period' => 'Taahhüt Süresi',
    'commitment' => 'Taahhüt',
    'commitless' => 'Taahhütsüz',
    'commitments' => [
        0 => 'Taahhütsüz',
        6 => '6 Ay',
        12 => '12 Ay',
        24 => '24 Ay',
        27 => '27 Ay',
    ],
    "modem" => 'Modem',
    'modem_serial' => 'Modem Seri Numarası',
    "modems" => [
        1 => "Yok",
        2 => "ADSL (:price)",
        3 => "VDSL (:price)",
        4 => "Fiber",
        5 => "Uydu Modem"
    ],
    'modem_price' => "Modem Kira Ücreti",
    "modem_payment" => 'Modem Ücreti Ödeme Şekli',
    "modem_payments" => [
        1 => "Peşin",
        2 => "İlk aya yansıt",
        3 => "İlk iki aya yansıt",
        4 => "İlk üç aya yansıt"
    ],
    "setup_payment" => 'Kurulum/Aktivasyon Ücreti (:price) Ödeme Şekli',
    "setup_payments" => [
        1 => "Peşin",
        2 => "İlk aya yansıt",
        3 => "İlk iki aya yansıt",
        4 => "İlk üç aya yansıt"
    ],
    "pre_payment" => "Ön ödemeli",
    "summer_campaing_payment" => 'Yaz Kampanyası Ücreti (:price) Ödeme Şekli',
    "summer_campaing_payments" => [
        1 => "Peşin",
        2 => "İlk aya yansıt",
        3 => "İlk iki aya yansıt",
    ],
    'subscriber' => 'Abone',
    'subscription_no' => 'Abonelik Numarası',
    'payment_status' => 'Ödeme Durumu',
    'payment_type' => 'Ödeme Şekli',
    'advance_paymented_price' => 'Peşin Ödenen Tutar',
    'setup' => 'Kurulum',
    'setup_informations' => 'Kurulum Bilgileri',
    'subscription_duration' => 'Abonelik Aralığı',
    'payable' => 'Ödenecek',
    // Subscription Attributes

    // Credit Card Attributes
    'card' => [
        'number' => 'Kart Numarası',
        'name_surname' => 'Kartın Üzerindeki Ad Soyad',
        'expire_date' => 'Kartın Son Kullanma Tarihi',
        'security_code' => 'CVV Güvenlik Kodu',
        'auto_payment' => 'Otomatik Ödeme',
    ],
    // Credit Card Attributes End
];
