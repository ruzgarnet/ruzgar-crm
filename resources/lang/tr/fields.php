<?php

return [
    // Global Attributes
    'send' => 'Gönder',
    'actions' => 'İşlemler',
    'none' => 'Yok',
    // Global Attributes End

    // Identity Attributes
    'name' => 'İsim',
    'first_name' => 'Ad',
    'last_name' => 'Soyad',
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
        2 => "ADSL (" . print_money(setting('service.modem.adsl')) . ")",
        3 => "VDSL (" . print_money(setting('service.modem.vdsl')) . ")",
        4 => "Fiber"
    ],
    "modem_payment" => 'Modem Ücreti Ödeme Şekli',
    "modem_payments" => [
        1 => "Peşin",
        2 => "İlk aya yansıt",
        3 => "İlk iki aya yansıt",
        4 => "İlk üç aya yansıt"
    ],
    "setup_payment" => 'Kurulum/Aktivasyon Ücreti (' . print_money(setting('service.setup.payment')) . ') Ödeme Şekli',
    "setup_payments" => [
        1 => "Peşin",
        2 => "İlk aya yansıt",
        3 => "İlk iki aya yansıt",
        4 => "İlk üç aya yansıt"
    ],
    "pre_payment" => "Ön ödemeli",
    "summer_campaing_payment" => 'Yaz Kampanyası Ücreti (' . print_money(setting('service.summer.campaing.payment')) . ') Ödeme Şekli',
    "summer_campaing_payments" => [
        1 => "Peşin",
        2 => "İlk aya yansıt",
        3 => "İlk iki aya yansıt",
    ],
    // Subscription Attributes
];
