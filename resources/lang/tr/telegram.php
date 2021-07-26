<?php

return [
    'delete_payment' => ":full_name - :id_no \nMüşterimizin :payment_id numaralı ödemesi silindi. \nSilme Nedeni : :description \nİşlemi Gerçekleştiren Kullanıcı : :username",
    'cancel_subscription' => ":full_name - :id_no kimlik numaralı müşterimizin kaydı silindi. \nSilme Nedeni : :description \nİşlemi Gerçekleştiren Kullanıcı : :username",
    'add_subscription' => ":id_no - :full_name \nİşlemi gerçekleştiren kullanıcı : :username \nMüşteri Temsilcisi : :customer_staff",
    'add_application' => ":full_name adlı bir kullanıcı RüzgarNET hakkında bilgi almak istiyor. \nTelefon Numarası : :telephone \nİlgilenecek Kişi : :username",
    'add_fault_record' => ":id_no - :full_name adlı müşterimiz tarafından bir arıza kaydı oluşturuldu. \nTelefon Numarası : :telephone \nMüşteri Temsilcisi : :customer_staff",
    'add_fault_record_description' => "Arıza İçeriği Aşağıdaki Şekildedir \n\":description\"",
    'edit_fault_record' => ":serial_number [:status] \nAbone : :id_no - :full_name \nDetay : :description \nKullanıcı : :username",
    'infrastructure' => ":message \nAdı Soyadı : :full_name \nTelefon Numarası : :telephone \nİl : :city \nBBK : :bbk",
    'application_cancel' => "Yeni bir iptal başvurusu oluşturuldu. \nAdı Soyadı : :full_name \nTelefon Numarası : :telephone",
    'application_subscription' => "Adı Soyadı :full_name \nTelefon Numarası : :telephone \n:username",
    'add_freeze' => "Yeni bir dondurma işlemi gerçekleştirildi. \nMüşteri : :full_name \nTarife : :subscription \nPersonel : :username",
    'unfreeze' => "Dondurulan abonelik aktif edildi. \nMüşteri : :full_name \nTarife : :subscription \nPersonel : :username",
    'add_request_message' => " :username adlı kullanıcı tarafından :role yetkili kişiler için bir talep oluşturuldu.\n İçerik: :description \nDurumu : :status",
    'edit_request_message' => " :username adlı kullanıcı tarafından açılan talepde :user adlı kişi işlem yaptı. \n İçerik: :description \nDurumu : :status",
    'edit_fault' => ":full_name adlı müşterimiz tarafından oluşturulan :serial_number seri numaralı arıza kaydının durumu :status olarak değiştirildi. \nDetay : :detail \nİşlemi Gerçekleştiren Kullanıcı : :username",
    'payment_received' => "Başarılı bir ödeme gerçekleştirildi. \nT.C. Kimlik Numarası : :id_no \nAdı Soyadı : :full_name \nTutar : :price TL \nMarka : :category"
];
