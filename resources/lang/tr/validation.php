<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'   => ':attribute kabul edilmelidir.',
    'active_url' => ':attribute geçerli bir URL olmalıdır.',
    'after'      => ':attribute değeri, :date tarihinden daha sonraki bir tarih olmalıdır.',
    'after_or_equal' => ':attribute değeri, :date tarihinden daha sonraki veya aynı tarih olmalıdır.',
    'alpha'      => ':attribute sadece harflerden oluşmalıdır.',
    'alpha_dash' => ':attribute sadece harfler, rakamlar ve tireler ve altçizgilerden oluşmalıdır.',
    'alpha_num'  => ':attribute sadece harfler ve rakamlar içermelidir.',
    'array'      => ':attribute dizi olmalıdır.',
    'before'     => ':attribute değeri, :date tarihinden daha önceki bir tarih olmalıdır.',
    'before_or_equal' => ':attribute değeri, :date tarihinden daha önceki veya aynı tarih olmalıdır.',
    'between'    => [
        'numeric' => ':attribute :min - :max arasında olmalıdır.',
        'file'    => ':attribute :min - :max arasındaki kilobayt değeri olmalıdır.',
        'string'  => ':attribute :min - :max arasında karakterden oluşmalıdır.',
        'array'   => ':attribute :min - :max arasında nesneye sahip olmalıdır.',
    ],
    'boolean'        => ':attribute alanı sadece doğru veya yanlış olabilir.',
    'confirmed'      => ':attribute tekrarı eşleşmiyor.',
    'date'           => ':attribute geçerli bir tarih olmalıdır.',
    'date_equals'    => ':attribute değeri, :date tarihine eşit olmalıdır.',
    'date_format'    => ':attribute :format biçimi ile eşleşmiyor.',
    'different'      => ':attribute ile :other birbirinden farklı olmalıdır.',
    'digits'         => ':attribute :digits rakam olmalıdır.',
    'digits_between' => ':attribute :min ile :max arasında rakam olmalıdır.',
    'dimensions'     => ':attribute geçersiz resim ölçülerine sahiptir.',
    'distinct'       => ':attribute alanı tekrarlanan bir değere sahiptir.',
    'email'          => ':attribute doğru bir e-posta olmalıdır.',
    'ends_with'      => ':attribute şunlardan biri ile bitmelidir: :values.',
    'exists'         => 'Seçili olan :attribute geçersiz.',
    'file'           => ':attribute dosya olmalıdır.',
    'filled'         => ':attribute alanı bir değer içermelidir.',
    'gt' => [
        'numeric' => ':attribute, :value değerinden daha büyük olmalıdır.',
        'file'    => ':attribute, :value kilobayt değerinden daha büyük olmalıdır.',
        'string'  => ':attribute, :value karakterden daha uzun olmalıdır.',
        'array'   => ':attribute, :value elemandan daha fazlasına sahip olmalıdır.',
    ],
    'gte' => [
        'numeric' => ':attribute, :value veya daha büyük bir değerde olmalıdır.',
        'file'    => ':attribute, :value veya daha büyük kilobaytta olmalıdır.',
        'string'  => ':attribute, :value veya daha fazla karakterden oluşmalıdır.',
        'array'   => ':attribute, :value veya daha fazla elemana sahip olmalıdır.',
    ],
    'image'          => ':attribute resim dosyası olmalıdır.',
    'in'             => ':attribute değeri geçersiz.',
    'in_array'       => ':attribute değeri :other içinde mevcut değil.',
    'integer'        => ':attribute rakam olmalıdır.',
    'ip'             => ':attribute geçerli bir IP adresi olmalıdır.',
    'ipv4'           => ':attribute geçerli bir IPv4 adresi olmalıdır.',
    'ipv6'           => ':attribute geçerli bir IPv6 adresi olmalıdır.',
    'json'           => ':attribute geçerli bir JSON dizesi olmalıdır.',
    'lt' => [
        'numeric' => ':attribute, :value değerinden daha küçük olmalıdır.',
        'file'    => ':attribute, :value kilobayt değerinden daha küçük olmalıdır.',
        'string'  => ':attribute, :value karakterden daha kısa olmalıdır.',
        'array'   => ':attribute, :value elemandan daha azına sahip olmalıdır.',
    ],
    'lte' => [
        'numeric' => ':attribute, :value veya daha küçük bir değerde olmalıdır.',
        'file'    => ':attribute, :value veya daha küçük kilobaytta olmalıdır.',
        'string'  => ':attribute, :value veya daha az karakterden oluşmalıdır.',
        'array'   => ':attribute, :value veya daha az elemana sahip olmalıdır.',
    ],
    'max'            => [
        'numeric' => ':attribute değeri :max değerinden büyük olmamalıdır.',
        'file'    => ':attribute değeri :max kilobayt değerinden büyük olmamalıdır.',
        'string'  => ':attribute değeri en fazla :max karakter uzunluğunda olmalıdır.',
        'array'   => ':attribute değeri :max adedinden fazla nesneye sahip olmamalıdır.',
    ],
    'mimes' => ':attribute dosya biçimi :values olmalıdır.',
    'mimetypes'  => ':attribute dosya biçimi :values olmalıdır.',
    'min'   => [
        'numeric' => ':attribute değeri en az :min değerinde olmalıdır.',
        'file'    => ':attribute değeri en az :min kilobayt değerinde olmalıdır.',
        'string'  => ':attribute değeri en az :min karakter uzunluğunda olmalıdır.',
        'array'   => ':attribute en az :min nesneye sahip olmalıdır.',
    ],
    'not_in'               => 'Seçili :attribute geçersiz.',
    'not_regex'            => ':attribute biçimi geçersiz.',
    'numeric'              => ':attribute rakam olmalıdır.',
    'password'             => 'Parola hatalı.',
    'present'              => ':attribute alanı var olmalıdır.',
    'regex'                => ':attribute biçimi geçersiz.',
    'required'             => ':attribute alanı gereklidir.',
    'required_if'          => ':attribute alanı, :other :value değerine sahip olduğunda zorunludur.',
    'required_unless'      => ':attribute alanı, :other :values değerine sahip olmadığında zorunludur.',
    'required_with'        => ':attribute alanı :values varken zorunludur.',
    'required_with_all'    => ':attribute alanı :values varken zorunludur.',
    'required_without'     => ':attribute alanı :values yokken zorunludur.',
    'required_without_all' => ':attribute alanı :values yokken zorunludur.',
    'same'                 => ':attribute ile :other eşleşmelidir.',
    'size'                 => [
        'numeric' => ':attribute :size olmalıdır.',
        'file'    => ':attribute :size kilobayt olmalıdır.',
        'string'  => ':attribute :size karakter olmalıdır.',
        'array'   => ':attribute :size nesneye sahip olmalıdır.',
    ],
    'starts_with' => ':attribute şunlardan biri ile başlamalıdır: :values.',
    'string'   => ':attribute karakterlerden oluşmalıdır.',
    'timezone' => ':attribute geçerli bir zaman bölgesi olmalıdır.',
    'unique'   => ':attribute daha önceden kayıt edilmiş.',
    'uploaded' => ':attribute yüklenirken hata oluştu.',
    'url'      => ':attribute biçimi geçersiz.',
    'uuid'     => ':attribute geçerli bir UUID olmalıdır.',

    /*
    |--------------------------------------------------------------------------
    | Custom Rule Messages
    |--------------------------------------------------------------------------
    |
    */

    'telephone' => ':attribute geçerli değildir.',
    'tcno' => ':attribute geçerli değildir.',
    'district' => ':attribute geçersiz.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'username' => 'Kullanıcı adı',
        'email' => 'E-Posta adresi',
        'password' => 'Şifre',
        'name' => 'İsim',
        'tax_number' => 'Vergi numarası',
        'city_id' => 'İl',
        'district_id' => 'İlçe',
        'address' => 'Adres',
        'started_at' => 'Başlama tarihi',
        'ended_at' => 'Bitiş tarihi',
        'released_at' => 'Çıkış tarihi',
        'telephone' => 'Telefon numarası',
        'dealer_id' => 'Bayi',
        'first_name' => 'Ad',
        'last_name' => 'Soyad',
        'secondary_telephone' => 'İkincil telefon numarası',
        'birthday' => 'Doğum tarihi',
        'identification_number' => 'Kimlik numarası',
        'gender' => 'Cinsiyet',
        'key' => 'Anahtar',
        'title' => 'Başlık',
        'view' => 'View',
        'options' => 'Seçenekler',
        'slug' => 'Slug',
        'content' => 'İçerik',
        'price' => 'Tutar',
        'new_price' => 'Yeni tutar',
        'meta_title' => 'Meta başlığı',
        'meta_description' => 'Meta açıklaması',
        'meta_keywords' => 'Meta anahtar kelimeleri',
        'contract_type_id' => 'Sözleşme tipi',
        'type' => 'Kayıt tipi',
		'parent_id' => 'Üst kategori',
        'message' => 'Mesaj',
        'category_id' => 'Kategori',
        'model' => 'Model',
        'staff_id' => 'Personel',
        'service_id' => 'Hizmet',
        'customer_id' => 'Müşteri',
        'bbk_code' => 'BBK Kodu',
        'commitment' => 'Taahhüt süresi',
        'options' => [
            'modem' => 'Modem',
            'modem_serial' => 'Modem seri numarası',
            'setup_payment' => 'Kurulum/Aktivasyon ücreti ödeme şekli',
            'pre_payment' => 'Ön ödeme',
            'campaing_payment' => 'Kampanya ücreti ödeme şekli',
            'modem_model' => 'Modem marka ve modeli',
            'address' => 'Adres',
            'commitment' => 'Kampanya taahhüt şartı',
            'price' => 'Kampanya tutarı',
            'duration' => 'Kampanya süresi'
        ],
        'card' => [
            'number' => 'Kart numarası',
            'full_name' => 'Ad soyad',
            'expire_date' => 'Son kullanma tarihi',
            'security_code' => 'CVV  kodu',
            'auto_payment' => 'Otomatik ödeme',
        ],
        'description' => 'Açıklama',
        'payment' => 'Tutar',
        'download' => 'İndirme hızı',
        'upload' => 'Yükleme hızı',
        'original_price' => 'Kampanyasız fiyatı',
        'fault_type_id' => 'Arıza kayıt tipi',
        'role_id' => 'Yetki'
    ],

];
