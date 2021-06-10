<?php

namespace App\Http\Controllers\Admin;

use App\Classes\SMS_Api;
use App\Classes\Telegram;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function infrastructure()
    {
        return view('admin.infrastructure');
    }

    public function infrastructure_submit(Request $request)
    {
        $cities = array(
            15 => 'BURDUR',
            26 => 'ESKİŞEHİR',
            18 => 'ÇANKIRI',
            80 => 'OSMANİYE',
            41 => 'KOCAELİ',
            27 => 'GAZİANTEP',
            31 => 'HATAY',
            38 => 'KAYSERİ',
            29 => 'GÜMÜŞHANE',
            54 => 'SAKARYA',
            16 => 'BURSA',
            69 => 'BAYBURT',
            17 => 'ÇANAKKALE',
            57 => 'SİNOP',
            74 => 'BARTIN',
            503 => 'MAĞUSA (KIBRIS)',
            33 => 'MERSİN',
            51 => 'NİĞDE',
            42 => 'KONYA',
            60 => 'TOKAT',
            2 => 'ADIYAMAN',
            6 => 'ANKARA',
            66 => 'YOZGAT',
            52 => 'ORDU',
            53 => 'RİZE',
            1 => 'ADANA',
            40 => 'KIRŞEHİR',
            76 => 'IĞDIR',
            45 => 'MANİSA',
            21 => 'DİYARBAKIR',
            64 => 'UŞAK',
            501 => 'LEFKOŞE (KIBRIS)',
            5 => 'AMASYA',
            24 => 'ERZİNCAN',
            32 => 'ISPARTA',
            502 => 'GİRNE (KIBRIS)',
            23 => 'ELAZIĞ',
            78 => 'KARABÜK',
            30 => 'HAKKARİ',
            36 => 'KARS',
            67 => 'ZONGULDAK',
            68 => 'AKSARAY',
            44 => 'MALATYA',
            10 => 'BALIKESİR',
            20 => 'DENİZLİ',
            49 => 'MUŞ',
            73 => 'ŞIRNAK',
            48 => 'MUĞLA',
            59 => 'TEKİRDAĞ',
            39 => 'KIRKLARELİ',
            56 => 'SİİRT',
            28 => 'GİRESUN',
            63 => 'ŞANLIURFA',
            9 => 'AYDIN',
            72 => 'BATMAN',
            13 => 'BİTLİS',
            3 => 'AFYONKARAHİSAR',
            8 => 'ARTVİN',
            4 => 'AĞRI',
            77 => 'YALOVA',
            50 => 'NEVŞEHİR',
            61 => 'TRABZON',
            58 => 'SİVAS',
            7 => 'ANTALYA',
            37 => 'KASTAMONU',
            47 => 'MARDİN',
            46 => 'KAHRAMANMARAŞ',
            25 => 'ERZURUM',
            75 => 'ARDAHAN',
            81 => 'DÜZCE',
            55 => 'SAMSUN',
            19 => 'ÇORUM',
            65 => 'VAN',
            14 => 'BOLU',
            43 => 'KÜTAHYA',
            11 => 'BİLECİK',
            34 => 'İSTANBUL',
            79 => 'KİLİS',
            62 => 'TUNCELİ',
            12 => 'BİNGÖL',
            22 => 'EDİRNE',
            71 => 'KIRIKKALE',
            70 => 'KARAMAN',
            35 => 'İZMİR',
        );

        $json_results = array();

        $door_id = $request->input('doors');
        $phone = $request->input('telephone');
        $full_name = $request->input('full_name');
        $city_code = $request->input('cities');

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://185.205.17.15/index.php");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            "doors" => $door_id
        ));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close($ch);

        $json_results = json_decode($server_output);

        $port_statu = false;
        $port_speed = 0;

        $message = "";

        if ($json_results->results->adsl->statu) {
            $port_statu = true;
            $json_results->results->adsl->max_speed = floor($json_results->results->adsl->max_speed / 1000);
            $port_speed = floor($json_results->results->adsl->max_speed);
            if ($port_speed == "NaN") {
                $json_results->results->adsl->max_speed = "BİLİNMEYEN HIZ";
                $port_speed = "BİLİNMEYEN HIZ";
            }
            $message .= "[ADSL - " . $port_speed . "]";
        }
        if ($json_results->results->vdsl->statu) {
            $port_statu = true;
            $json_results->results->vdsl->max_speed = floor($json_results->results->vdsl->max_speed / 1000);
            $port_speed = floor($json_results->results->vdsl->max_speed);
            if ($port_speed == "NaN") {
                $json_results->results->vdsl->max_speed = "BİLİNMEYEN HIZ";
                $port_speed = "BİLİNMEYEN HIZ";
            }
            $message .= " | [VDSL - " . $port_speed . "]";
        }
        if ($json_results->results->fiber->statu) {
            $port_statu = true;
            $port_speed = 0;
            $message .= "Kullanıcı fiber altyapıya sahiptir.";
        }

        if($port_statu)
		{
            // $sms = new SMS_Api("", "");
			// $sms->submit(
            //     "RUZGARNET",
			// 	"Binanızda RüzgarNET olarak hizmetimiz mevcuttur. İsterseniz, www.ruzgarnet.com.tr websitemiz üzerinden inceleyip, abone olabilirsiniz. Sizleri de MUTLU abonelerimiz arasında görmekten gurur duyarız. RUZGARNET 0216 205 06 06",
			// 	array($phone)
			// );

            // $telegram = new Telegram();
            // $telegram->send_message(
            //     "1046241971",
            //     $message." Adı Soyadı : ".$full_name." - Telefon Numarası : ".$phone." - İl : ".$cities[$city_code]." - BBK : ".$door_id
            // );
		}

        echo json_encode($json_results);
    }

    public function load(Request $request)
    {
        $id = $request->input('id');
        $type = $request->input('type');

        if ($id == "-1") {
            $server_output = '{"error":false, "results":{}}';
        } else {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "http://185.205.17.15/functions/load.php");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, array(
                "id" => $id,
                "type" => $type
            ));

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $server_output = curl_exec($ch);
            curl_close($ch);
        }

        echo $server_output;
    }

    /**
     * Searchs customer
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function search(Request $request)
    {
        $search = $request->input("q");

        $customer = new Customer();
        $fields = $customer->getFields();
        $fields[] = DB::raw("CONCAT(`first_name`, ' ', `last_name`)");
        $rows = $customer->where(function ($query) use ($fields, $search) {
            foreach ($fields as $field) {
                $query->orWhere($field, 'LIKE', "%{$search}%");
            }
        })->limit(10)->get();

        $data = [];

        if (count($rows)) {
            foreach ($rows as $row) {
                $data[] = [
                    'title' => $row->select_print,
                    'link' => route('admin.customer.show', $row)
                ];
            }
        }

        return $data;
    }
}
