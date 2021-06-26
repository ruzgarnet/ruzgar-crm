<?php

return [
    'delete_payment' => ":full_name - :id_no \nMüşterimizin :payment_id numaralı ödemesi silindi. \nSilme Nedeni : :description \nİşlemi Gerçekleştiren Kullanıcı : :username",
    'cancel_subscription' => ":full_name - :id_no kimlik numaralı müşterimizin kaydı silindi. \nSilme Nedeni : :description \nİşlemi Gerçekleştiren Kullanıcı : :username",
    'add_subscription' => ":id_no - :full_name \nİşlemi gerçekleştiren kullanıcı : :username \nMüşteri Temsilcisi : :customer_staff",
    'add_application' => ':full_name adlı bir kullanıcı RüzgarNET hakkında bilgi almak istiyor. Telefon Numarası : :telephone İlgilenecek Kişi : :username',
    'add_fault_record' => ':id_no - :full_name adlı müşterimiz tarafından bir arıza kaydı oluşturuldu. Telefon Numarası : :telephone Müşteri Temsilcisi : :customer_staff',
    'add_fault_record_description' => "Arıza İçeriği Aşağıdaki Şekildedir \n\":description\"",
    'edit_fault_record' => ":serial_number [:status] \nAbone : :id_no - :full_name \nDetay : :description \nKullanıcı : :username",
    'infrastructure' => ":message Adı Soyadı : :full_name  - Telefon Numarası : :telephone - İl : :city - BBK : :bbk",
    'application_cancel' => "Yeni bir iptal başvurusu oluşturuldu. \nAdı Soyadı : :full_name \nTelefon Numarası : :telephone"
];
