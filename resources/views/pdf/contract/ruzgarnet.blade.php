<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Sözleşme</title>
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: sans-serif;
            font-size: 11px;
            line-height: 14px;
        }

        p {
            margin-bottom: 3px;
        }

        table {
            width: 100%;
            caption-side: bottom;
            border-collapse: collapse;
            margin-bottom: 3px;
        }

        table td,
        table th {
            padding: 5px;
            border: 1px solid #454545;
            vertical-align: middle;
        }

        ul,
        ol {
            padding-left: 15px;
            margin-bottom: 3px;
        }

        li {
            margin-bottom: 1px;
        }

        .table-signature th {
            text-align: center;
            font-weight: bold;
        }

        .title {
            text-align: center;
            background-color: #f15a29;
            color: #fff;
            padding: 3px;
            display: block;
            width: 33.333333%;
            margin-bottom: 3px;
        }

        .title.big-title {
            width: 100%;
        }

        .user_table td {
            padding: 12px 0;
        }

        .user_table td,
        .user_table th {
            text-align: center;
        }

    </style>
</head>

<body>
    <div class="page">
        <div id="header">
            <table>
                <tr style="border: none;">
                    <td style="width:140mm;border:none;" rowspan="2"><img src="assets/images/ruzgar-logo-contract.jpg" width="180"
                            alt=""></td>
                    <td style="width: 70mm; text-align:center;border:none;"><img
                            src="data:image/png;base64, {{ $barcode }}" alt=""></td>
                </tr>
                <tr style="border: none;">
                    <td style="font-size:14px;text-align:center;border:none;">{{ $subscription->subscription_no }}
                    </td>
                </tr>
            </table>
        </div>
        <div class="title big-title">BİREYSEL TARİFE, İNDİRİM VE CİHAZ
            TAAHHÜTNAMESİ</div>
        <div>
            <table>
                <tbody>
                    <tr>
                        <td>Adı Soyadı</td>
                        <td>{{ $subscription->customer->full_name }}</td>
                        <td>T.C. Kimlik Numarası</td>
                        <td>{{ $subscription->customer->identification_number }}</td>
                    </tr>
                    <tr>
                        <td>İrtibat Telefonu</td>
                        <td>{{ $subscription->customer->telephone_print }}</td>
                        <td>E-posta Adresi</td>
                        <td>{{ $subscription->customer->email }}</td>
                    </tr>
                    <tr>
                        <td>Cep Telefonu Numarası</td>
                        <td>{{ $subscription->customer->customerInfo->secondary_telephone_print }}</td>
                        <td>Bağlantı Tipi</td>
                        <td>ADSL/VDSL/Fiber</td>
                    </tr>
                    <tr>
                        <td>Bağlantı Adresi</td>
                        <td colspan="3">{{ $subscription->address }}</td>
                    </tr>
                    <tr>
                        <td>Cihaz Marka Model</td>
                        <td>{{ $subscription->getOption('modem_model', '-') }}</td>
                        <td>Cihaz Seri Numarası</td>
                        <td>{{ $subscription->getOption('modem_serial', '-') }}</td>
                    </tr>
                    <tr>
                        <td>BBK</td>
                        <td>{{ $subscription->bbk_code ?? '-' }}</td>
                        <td>Tarife</td>
                        <td>{{ $subscription->service->name }}</td>
                    </tr>
                    <tr>
                        <td>Sözleşme Süresi</td>
                        <td>@lang('fields.commitments.'.$subscription->commitment)</td>
                        <td>Hizmet Numarası</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="title">BİREYSEL TARİFE</div>
        <div>
            <table>
                <thead>
                    <tr>
                        <th>Tarife Adı</th>
                        <th>Bağlantı Hızı</th>
                        <th>Tarife Fiyatı</th>
                        <th>Kampanya Fiyatı</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $subscription->service->name }}</td>
                        <td>@lang('fields.speed_info', [
                            'download' => $subscription->service->download,
                            'upload' => $subscription->service->upload
                            ])</td>
                        <td>{{ $subscription->service->original_price }}</td>
                        <td>{{ $subscription->service->price }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p>Yukarıda belirtilen ücrete %18 KDV ve %10 ÖİV dahildir. RÜZGARFİBER,
            Akarnet Telekom San. Tic. Ltd. Şti nin tescilli markasıdır.</p>
        <p><b>İndirim Bedeli:</b> Kampanyada sunulan internet paketinin tarife
            ücreti ile kampanyalı ücret farkını göstermektedir. Yukarıdaki
            tabloda yer alan kampanyalı fiyat belirtilen 6 (altı), 12 (on iki),
            24 (yirmi dört) veya 27 (yirmi yedi) kampanya dönemi içerisinde
            geçerli olup, bu tarihten sonra tarifesi uygulanacaktır.</p>
        <p>Paket faturalı veya ön ödemeli 6 (altı), 12 (on iki), 24 (yirmi dört)
            veya 27 (yirmi yedi) sözleşmelerde geçerlidir. Kampanyadan sadece
            RÜZGARFİBER ile RÜZGARFİBER Abonelik sözleşmesini imzalamak
            suretiyle abonelik tesis eden veRÜZGARFİBER bireysel abonelik
            paketlerinden birini seçen yeni aboneler ile RÜZGARFİBER'e kampanya
            taahhüdü bulunmayan ve işbutaahhütnameyi imzalayarak RÜZGARFİBER
            bireysel abonelik paketlerinden bir tarife kullanan mevcut tüm
            RÜZGARFİBER abonelerifaydanalanabilecektir. Yukarıdaki tabloda yer
            alan kampanyalı tarife ücretlerine taahhüt süresince her ay
            yansıtılmak üzere damga vergisieklenecektir. Abone kampanya
            dahilinde almış olduğu cihazları abonelik bitiminde AKARNET Telekom
            Sanayi Ticaret Limited Şirketi'ine iadeetmek zorundadır. İndirim
            bedeli, taahhüt vermeniz durumunda 170 TL port tahsis bedeli ve 400
            (dört yüz) TL değerinde olan yerindekurulum bedelinin 60 TL'si
            faturanıza yansıtılır fakat sözleşme fesh edilirse kalan tutar cayma
            bedeline ek olarak tahsil edilecektir.</p>
        <ol>
            <li>Seçilen/geçilen tarifede belirlenmiş kampanya fiyatına göre
                faturalandırmaya ilk fatura döneminden sonra başlanacaktır.
                RÜZGARFİBERfatura ödeme sistemi kredi kartı ile öde-kullan
                sistemidir. Otomatik ödeme talimatı sistemi ile çalışmaktadır.
                RÜZGARFİBER aboneye vermişolduğu cihazı kullanım sona erdiğinde
                sağlam ve çalışır şekilde geri alacaktır. Aksi bir durumda abone
                cihaz veya cihazların ücreti olan 1500TL veya güncel rayiç
                bedelini ödeyeceğini kabul ve taahhüt eder. Kampanya kapsamında
                tercih ettiği tarife paketinin ücret olarak altınainmemek
                koşuluyla hız/paketdeğişikliği yapabilecektir. Söz konusu
                indirimler tarife kullanım ücretlerini kapsamakta olup, bağlantı
                ücretindeve abone tarafından seçilen tarifede yer alan diğer
                ücretlerde herhangi bir indirim söz konusu değildir.</li>
            <li>RÜZGARFİBER'de fatura kesim tarihi her ayın birinci günüdür.
                İlgili ayın on beşinci gününe gelindiğinde tercih ettiğiniz
                yöntem ilefaturanızın ödemesinin yapılması gerekir. Takip eden
                dört gün içerisinde ödeme alınamazsa internet hizmeti geçici
                olarak durdurulur ve 45(kırk beş) TL açma-kapama hizmet bedeli o
                ayın faturasına yansıtılır. İlgili ayın yirminci gününde hala
                ödeme gerçekleştirilmezseRÜZGARFİBER yasal yollara başvurabilir.
            </li>
            <li>Tele satışlarda RüzgarNET tarafından abonenin onayı ile alınan
                ses kayıtları yasal belge olarak kabul edilecektir.</li>
            <li>RÜZGARFİBER, internet hizmetini Türk Telekom A.Ş. altyapısını
                kullanarak ADSL, VDSL ve FIBER olarak sağlar. Bu konudaki
                çözümortağı Türk Telekom A.Ş. dir. Abone, kampanya uygulaması
                çerçevesinde RÜZGARFİBER'den edindiği internet hizmetini belli
                il ve ilçelerinsınırları içerisinde Türk Telekom A.Ş. nin teknik
                altyapısının bulunduğu başka bir lokasyonda kullanma hakkına
                sahiptir.</li>
            <li>RÜZGARFİBER tarafından hizmetin teknik imkânsızlıklar nedeniyle
                abonenin hizmetten yararlandığı yerde sürekli olarak
                verilememesidurumu hariç olmak üzere, abonenin herhangi bir
                sebeple 6 (altı), 12 (on iki), 24 (yirmi dört) veya 27 (yirmi
                yedi) aylık süre dolmadanRÜZGARFİBER aboneliğini sona erdirmesi
                veya borcunu ödememesi gibi nedenlerle RÜZGARFİBER tarafından
                müşterinin aboneliğine sonverilmesi (sözleşmenin feshi)
                durumları başta olmak üzere, aboneliğin herhangi bir sebeple 6
                (altı), 12 (on iki), 24 (yirmi dört) veya 27 (yirmiyedi) aylık
                süreden önce sona ermesi, abonelikten vazgeçme talebinin yazılı
                olarak RÜZGARFİBER'e iletilmesi ve taahhüt kapsamındaverilen
                cihaz veya cihazların çalınması, abone kaynaklı nedenlerden
                dolayı bozulması durumunda, kampanya nedeniyle kiralık olarak
                verilencihazveya cihazların ücreti, (varsa)ödenmemiş bağlantı
                ücreti ve taksitleri, (varsa) geriye dönük kullanım ücretleri,
                (varsa) tarifede yeralandiğer kullanımlara ait borçları aboneden
                tahsil edilecektir. Abone, abonelikten vazgeçme taleplerini
                YAZILI olarak RÜZGARFİBER'eiletmekle yükümlüdür.</li>
            <li>Sosyal medya (Facebook, Twitter, Instagram vb.) veya
                sikayetvar.com gibi tüketici platformlarında, ticari itibarımızı
                etkileyecek asılsız yorumların yapılması durumunda, aboneliğin
                tek taraflı fesh edileceğini ve cayma bedeli(yerinde kurulum,
                port tahsis, tarife indirim, yargı ve tazmin bedelleri)ni peşin
                olarak ödeyeceğimi kabul ve taahhüt ederim.</li>
        </ol>
        <div>
            <table class="user_table">
                <thead>
                    <tr>
                        <th>Marka Model</th>
                        <th>Seri Numarası</th>
                        <th>Modem Kiralama Bedeli</th>
                        <th>Yerinde Kurulum Bedeli</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $subscription->getOption('modem_model') }}</td>
                        <td>{{ $subscription->getOption('modem_serial') }}</td>
                        <td>{{ $subscription->getOption('modem_price') }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div>
            <table class="table-signature">
                <thead>
                    <tr>
                        <th>Yetkili Adı Soyadı İmza</th>
                        <th>Müşteri Adı Soyadı İmza</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="height:40px;"></td>
                        <td style="height:40px;"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="page" style="page-break-before: always;">
        <p class="title big-title">BİREYSEL TARİFE, İNDİRİM VE CİHAZ
            TAAHHÜTNAMESİ EKİ - 1
        </p>
        <ul>
            <li>Bu kampanya kapsamında aboneye kablolu ya da kablosuz modem veya
                cihaz satılmayacaktır. Eğer abone cihaz alırsa 24
                aylıkaboneliklerde ADSL, VDSL modem kiralama bedeli 7 (yedi) TL,
                6-12 aylık aboneliklerde 9 (dokuz) TL'dir. Fiber modem ise tüm
                aboneliklerde13 (on üç) TL aylık faturasına ek olarak
                ücretlendirilecektir. Aboneliğin sona ermesine istinaden
                RÜZGARFİBER cihaz veya cihazları sağlamve çalışır bir şekilde
                iade alacaktır. Aksi takdirde abone cihaz veya cihazlara ait
                olan (1500 TL)ücreti ödemekle yükümlüdür.</li>
            <li>RÜZGARFİBER, mevcut tarifelerinde, fiyat ve kampanya şartlarında
                aboneyi 30 (otuz) gün önceden bilgilendirmek koşuluyla
                değişiklikyapma hakkını saklı tutar.</li>
            <li>Seçilen paketteki belirlenmiş kampanya fiyatına göre
                faturalandırılma öde-kullan şeklindedir. Abone olunan tarihten,
                ilk fatura tarihinekadar geçen süredeki ücret, kampanya
                dahilinde seçilen paketin belirtilen indirimli internet hizmeti
                tarifesinden hesaplanarak faturaedilecektir. Abone, 6, 12, 24
                veya 27 aylık dönem zarfında sadece kampanya kapsamında geçişe
                açık olan paketlere, kampanyaya giriştetercih ettiği tarife
                paketinin ücret olarak altına inmemek koşuluyla, hız/paket
                değişikliği yapabilecektir. Söz konusu indirimler aylık
                kullanımücretlerini kapsamakta olup, abone tarafından seçilen
                tarifede yer alan diğer ücretlerde herhangi bir indirim söz
                konusu değildir. Ücretli katmadeğerli servislere abone olunması
                durumunda, kullanım bedelleri faturaya ayrıca yansıtılır. İşbu
                kampanya taahhütnamesinden kaynaklanandamga vergisi taahhüt
                süresince taksitlere bölünecektir, ve abonenin faturasına
                yansıtılacaktır.</li>
            <li>İşletmeci veya abone tarafından, herhangi bir sebeple 6, 12, 24
                veya 27 aylık taahhüt süresi tamamen dolmadan önce; abonenin
                dahilolduğu kampanyanın ve/veya aboneliğinin iptal edilmesi
                ve/veya kampanya giriş paketinden daha düşük ücretli bir pakete
                geçilmesihalinde;taahhütnamenin abone tarafından imzalandığ
                tarihten itibaren taahhüde aykırılığın oluştuğu döneme kadar
                aboneye sağlananindirim, rayiç cihaz veya diğer faydaların
                bedellerinin tahsil edilmemiş kısmının toplamı aboneden tahsil
                edilecektir Ancak aboneden taahhütkapsamındatahsil edileceği
                belirlenen hizmet bedellerinin henüz tahakkuk etmemiş kısmının
                toplamı, bu tutardan düşük olmas halinde düşükolan tutaraboneden
                tahsil edilecektir. Ek olarak (varsa)kurulum ücretinin ve
                cihaz/servis/ürünlerin kalan toplamı ayrıca tahsil edilir.</li>
            <li>Abone kampanya koşullarına ve kampanya dahilindeki internet
                hizmet tarifeleri, geçiş ücreti vb. ile ilgili bilgilere 0850
                302 51 51 telefonnumarası, www.ruzgarfiber.com.tr veya Rüzgar
                Destek uygulaması üzerinden ulaşabilecektir.</li>
            <li>Ürün ve sözleşmeye ait kargo sürecinden kargo ve kurye şirketi
                sorumludur. RÜZGARFİBER'in herhangi bir sorumluluğu yoktur.</li>
            <li>Kiralık verilen modem abonelik bitiminde teslim edilmek
                zorundadır. Aksi takdirde modem için 1500 TL tahsil edilecektir.
            </li>
            <li>İşbu taahhütnamede yer almayan hususlarda abonelik sözleşmesinin
                hükümleri geçerlidir.</li>
            <li>Abone tarifesini değiştirirken sadece üst paketleri tercih
                edebilir. Tarife yükseltme işlemini yapabilmesi için Rüzgar
                Destek uygulaması'Tarife Yükselt' bölümünü kullanmalı veya
                şirket tarafına yazılı talimat göndermelidir. İşlem
                tamamlandıktan sonra faturalar yeni tarife ücretiüzerinden
                düzenlenir. Fatura kesim tarihi her ayın birinci günüdür ve
                abonenin faturasına ait ödeme, ilgili ayın on beşinci gününde
                belirlediğiödemeyöntemi(Kredi/Banka Kartı) ile
                gerçekleştirilecektir. Ödemesi gerçekleştirilemeyen aboneye,
                dört gün ek süre verilecektir. Bu süreiçerisinde hala ödeme
                alınamamış ise hizmet geçici olarak durdurulacaktır. Hizmetin
                durdurulmasından sonra gerçekleştirilmiş ödemelerdeabonenin, o
                ay faturasına ek hizmet bedeli yansıtılacaktır.</li>
        </ul>
        <p>*Akarnet Telekom Sanayi Ticaret Limited Şirketi tarafından açıklanan
            6698 sayılı Kişisel Verilerin Korunması Kanunu'na ilişkin
            aydınlatmametninin www.ruzgarfiber.com.tr web sitesinde tamamını
            okudum, anladım ve Akarnet Telekom'un Kişisel Verilerimi yukarıda
            belirtilenamaçlar çerçevesinde işlemesi konusunda bilgilendirildim.
            Bu kapsamda Kişisel Verilerimin 6698 sayılı Kişisel Verilerin
            KorunmasıKanunu'na uygun olarak Şirketiniz Akarnet Telekom Ltd. Şti.
            tarafından, gerekli bilgilerin yasalar gereğince muhafazası, Akarnet
            Telekom'unMüşterilerine ürün / hizmet sunması, tedarikçi ya da
            üreticilerden ürün ve/veya hizmet tedariki sağlaması ve/veya bu
            konuda sözleşmeli yada sözleşmesiz ticari ilişkilerin kurulması ve
            ifa edilmesi, ilgili belgeleri imzalayan tarafların tespiti ve
            kontrolü, bunlar kapsamındagerçekleştirilecek her türlü başvuru, iş
            ve işlemin sahibini ve muhatabını belirlemek üzere bilgilerin
            tespiti için kimlik, adres, vergi numarası vediğer bilgileri
            kaydetmek, kâğıt üzerinde veya elektronik ortamda gerçekleştirilecek
            iş ve işlemlere dayanak olacak bilgi ve belgeleridüzenlenmesi gibi
            amaçların gerçekleştirilmesi için her türlü kanallar aracılığıyla
            Aydınlatma Metninde er alan bilgiler ışığında işlenmesine vekanuni
            ya da hizmete ve/veya iş ilişkisine bağlı fiili gereklilikler
            halinde Aydınlatma Metninde belirtilen kişiler ile paylaşılmasına
            konu hakkındatereddüde yer vermeyecek şekilde aydınlatılmış ve bilgi
            sahibi olarak, açık rızamla onay veriyorum.*Şirketinizden almış
            bulunduğum hizmetten ya da hizmeti alan kişiye kefil olduğumdan
            dolayı aylık kullanım bedelimin aşağıda bilgilerinipaylaştığım
            tutarı belli olan meblağın kredi/banka kartımdan çekilmesine onay
            veriyorum. Almış olduğum hizmetler dahilinde imzaladığımsözleşmedeki
            şartları burada da geçerlidir. Abonelik sözleşmesi, Abonelik İndirim
            Taahhütnamesi, Kiralık Cihaz TaahhütnamesiRÜZGARFİBER'e oluşan her
            türlü borç tahsilatımın bu kredi/banka kartından çekim yapılarak
            tahsil edilmesini onaylıyorum.RÜZGARFİBER(Akarnet Telekom ltd. şti.)
            sözleşmem dahilinde faturalandırdığı tutarın kredi/banka kartımdan
            otomatik çekilmesine onayveriyorum. Kiralık olarak almış olduğum
            ürünlerin taahhüdünü bozmam, kaybetmem ya da hasarlı teslim etmem
            halinde ücretlerinin bilgilerinipaylaştığım kredi kartından
            çekilmesine onay veriyorum. Kredi/banka kartı ekstrem de
            Şirketimizin çözüm ortağı olduğu RüzgarNET veyaMoka Ödeme Kuruluşu
            olarak görünecektir.Tarafıma bilgi verilmiştir.</p>
        <ul>
            <li>Abone, 3D secure onayı ile ilk aktivasyon sırasında alınan
                tarife tutarı ve ön provizyon için alınan tarife bedelini veya
                ön provizyon içinalınmış olan bedeli aldığı hizmet süresince
                otomatik olarak geçilmesine onay vermiş sayılacaktır.</li>
            <li>Bu sözleşmeden doğan uyuşmazlıklarda Niğde icra dairesi ve
                mahkemelerinin yetkisini kabul ediyorum.</li>
        </ul>
        <h5 class="title">KREDİ/BANKA KARTI</h5>
        <div>
            <table class="user_table">
                <thead>
                    <tr>
                        <th>Adı Soyadı</th>
                        <th>Kart Numarası</th>
                        <th colspan="2">Son Kullanma Tarihi</th>
                        <th>Güvenlik Kodu</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p class="title big-title">TAAHHÜTNAME</p>
        <p>Yukarıda belirtilen ve seçmiş olduğum kampanya koşullarının okuduğumu
            ve aynen kabul ettiğimi; internet hizmetine ilişkin olarak
            tümyükümlülüklerimi zamanında ve tam olarak yerine getireceğimi,
            internet aboneliğimi sonlandırmaya karar verdiğimde veya
            RÜZGARFİBERtarafından teknik imkânsızlıklar nedeniyle hizmetin
            sürekli olarak verilememesi durumunda veya RÜZGARFİBER tarafından
            aboneliğime sonverilmesi veya herhangi bir nedenle aboneliğimin son
            bulması halinde, kampanya nedeniyle kampanyalı/indirimli süre
            boyunca kiralık olarakverilen cihaz veya cihazların ücretini,
            kullanım ücretinde yapılan indirimlerin toplamını,(varsa) geriye
            dönük kullanım ücretlerini, (varsa)ödenmemiş bağlantı ücreti ve
            taksitleri, (varsa) tarifede yer alan diğer kullanımlara ait
            borçları ilk talepte, nakden, defaten, herhangi birhüküm ihdasına
            gerek kalmaksızın RÜZGARFİBER'e ödemeyi gayrıkabili rücu olarak
            kabul,beyan ve taahhüt ederim.</p>
        <div>
            <table class="table-signature">
                <thead>
                    <tr>
                        <th>Yetkili Adı Soyadı İmza</th>
                        <th>Müşteri Adı Soyadı İmza</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="height:40px;"></td>
                        <td style="height:40px;"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
