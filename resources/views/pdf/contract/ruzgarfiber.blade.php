<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $subscription->select_print }}</title>
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
        <div class="title big-title">B??REYSEL TAR??FE, ??ND??R??M VE C??HAZ
            TAAHH??TNAMES??</div>
        <div>
            <table>
                <tbody>
                    <tr>
                        <td>Ad?? Soyad??</td>
                        <td>{{ $subscription->customer->full_name }}</td>
                        <td>T.C. Kimlik Numaras??</td>
                        <td>{{ $subscription->customer->identification_number }}</td>
                    </tr>
                    <tr>
                        <td>??rtibat Telefonu</td>
                        <td>{{ $subscription->customer->telephone_print }}</td>
                        <td>E-posta Adresi</td>
                        <td>{{ $subscription->customer->email }}</td>
                    </tr>
                    <tr>
                        <td>Cep Telefonu Numaras??</td>
                        <td>{{ $subscription->customer->customerInfo->secondary_telephone_print }}</td>
                        <td>Ba??lant?? Tipi</td>
                        <td>ADSL/VDSL/Fiber</td>
                    </tr>
                    <tr>
                        <td>Ba??lant?? Adresi</td>
                        <td colspan="3">{{ $subscription->address }}</td>
                    </tr>
                    <tr>
                        <td>Cihaz Marka Model</td>
                        <td>{{ $subscription->getOption('modem_model', '-') }}</td>
                        <td>Cihaz Seri Numaras??</td>
                        <td>{{ $subscription->getOption('modem_serial', '-') }}</td>
                    </tr>
                    <tr>
                        <td>BBK</td>
                        <td>{{ $subscription->bbk_code ?? '-' }}</td>
                        <td>Tarife</td>
                        <td>{{ $subscription->service->name }}</td>
                    </tr>
                    <tr>
                        <td>S??zle??me S??resi</td>
                        <td>@lang('fields.commitments.'.$subscription->commitment)</td>
                        <td>Hizmet Numaras??</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="title">B??REYSEL TAR??FE</div>
        <div>
            <table>
                <thead>
                    <tr>
                        <th>Tarife Ad??</th>
                        <th>Ba??lant?? H??z??</th>
                        <th>Tarife Fiyat??</th>
                        <th>Kampanya Fiyat??</th>
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
        <p>Yukar??da belirtilen ??crete %18 KDV ve %10 ????V dahildir. R??ZGARF??BER,
            Akarnet Telekom San. Tic. Ltd. ??ti nin tescilli markas??d??r.</p>
        <p><b>??ndirim Bedeli:</b> Kampanyada sunulan internet paketinin tarife
            ??creti ile kampanyal?? ??cret fark??n?? g??stermektedir. Yukar??daki
            tabloda yer alan kampanyal?? fiyat belirtilen 6 (alt??), 12 (on iki),
            24 (yirmi d??rt) veya 27 (yirmi yedi) kampanya d??nemi i??erisinde
            ge??erli olup, bu tarihten sonra tarifesi uygulanacakt??r.</p>
        <p>Paket fatural?? veya ??n ??demeli 6 (alt??), 12 (on iki), 24 (yirmi d??rt)
            veya 27 (yirmi yedi) s??zle??melerde ge??erlidir. Kampanyadan sadece
            R??ZGARF??BER ile R??ZGARF??BER Abonelik s??zle??mesini imzalamak
            suretiyle abonelik tesis eden veR??ZGARF??BER bireysel abonelik
            paketlerinden birini se??en yeni aboneler ile R??ZGARF??BER'e kampanya
            taahh??d?? bulunmayan ve i??butaahh??tnameyi imzalayarak R??ZGARF??BER
            bireysel abonelik paketlerinden bir tarife kullanan mevcut t??m
            R??ZGARF??BER abonelerifaydanalanabilecektir. Yukar??daki tabloda yer
            alan kampanyal?? tarife ??cretlerine taahh??t s??resince her ay
            yans??t??lmak ??zere damga vergisieklenecektir. Abone kampanya
            dahilinde alm???? oldu??u cihazlar?? abonelik bitiminde AKARNET Telekom
            Sanayi Ticaret Limited ??irketi'ine iadeetmek zorundad??r. ??ndirim
            bedeli, taahh??t vermeniz durumunda 170 TL port tahsis bedeli ve 400
            (d??rt y??z) TL de??erinde olan yerindekurulum bedelinin 60 TL'si
            faturan??za yans??t??l??r fakat s??zle??me fesh edilirse kalan tutar cayma
            bedeline ek olarak tahsil edilecektir.</p>
        <ol>
            <li>Se??ilen/ge??ilen tarifede belirlenmi?? kampanya fiyat??na g??re
                faturaland??rmaya ilk fatura d??neminden sonra ba??lanacakt??r.
                R??ZGARF??BERfatura ??deme sistemi kredi kart?? ile ??de-kullan
                sistemidir. Otomatik ??deme talimat?? sistemi ile ??al????maktad??r.
                R??ZGARF??BER aboneye vermi??oldu??u cihaz?? kullan??m sona erdi??inde
                sa??lam ve ??al??????r ??ekilde geri alacakt??r. Aksi bir durumda abone
                cihaz veya cihazlar??n ??creti olan 1500TL veya g??ncel rayi??
                bedelini ??deyece??ini kabul ve taahh??t eder. Kampanya kapsam??nda
                tercih etti??i tarife paketinin ??cret olarak alt??nainmemek
                ko??uluyla h??z/paketde??i??ikli??i yapabilecektir. S??z konusu
                indirimler tarife kullan??m ??cretlerini kapsamakta olup, ba??lant??
                ??cretindeve abone taraf??ndan se??ilen tarifede yer alan di??er
                ??cretlerde herhangi bir indirim s??z konusu de??ildir.</li>
            <li>R??ZGARF??BER'de fatura kesim tarihi her ay??n birinci g??n??d??r.
                ??lgili ay??n on be??inci g??n??ne gelindi??inde tercih etti??iniz
                y??ntem ilefaturan??z??n ??demesinin yap??lmas?? gerekir. Takip eden
                d??rt g??n i??erisinde ??deme al??namazsa internet hizmeti ge??ici
                olarak durdurulur ve 45(k??rk be??) TL a??ma-kapama hizmet bedeli o
                ay??n faturas??na yans??t??l??r. ??lgili ay??n yirminci g??n??nde hala
                ??deme ger??ekle??tirilmezseR??ZGARF??BER yasal yollara ba??vurabilir.
            </li>
            <li>Tele sat????larda R??zgarNET taraf??ndan abonenin onay?? ile al??nan
                ses kay??tlar?? yasal belge olarak kabul edilecektir.</li>
            <li>R??ZGARF??BER, internet hizmetini T??rk Telekom A.??. altyap??s??n??
                kullanarak ADSL, VDSL ve FIBER olarak sa??lar. Bu konudaki
                ????z??morta???? T??rk Telekom A.??. dir. Abone, kampanya uygulamas??
                ??er??evesinde R??ZGARF??BER'den edindi??i internet hizmetini belli
                il ve il??elerins??n??rlar?? i??erisinde T??rk Telekom A.??. nin teknik
                altyap??s??n??n bulundu??u ba??ka bir lokasyonda kullanma hakk??na
                sahiptir.</li>
            <li>R??ZGARF??BER taraf??ndan hizmetin teknik imk??ns??zl??klar nedeniyle
                abonenin hizmetten yararland?????? yerde s??rekli olarak
                verilememesidurumu hari?? olmak ??zere, abonenin herhangi bir
                sebeple 6 (alt??), 12 (on iki), 24 (yirmi d??rt) veya 27 (yirmi
                yedi) ayl??k s??re dolmadanR??ZGARF??BER aboneli??ini sona erdirmesi
                veya borcunu ??dememesi gibi nedenlerle R??ZGARF??BER taraf??ndan
                m????terinin aboneli??ine sonverilmesi (s??zle??menin feshi)
                durumlar?? ba??ta olmak ??zere, aboneli??in herhangi bir sebeple 6
                (alt??), 12 (on iki), 24 (yirmi d??rt) veya 27 (yirmiyedi) ayl??k
                s??reden ??nce sona ermesi, abonelikten vazge??me talebinin yaz??l??
                olarak R??ZGARF??BER'e iletilmesi ve taahh??t kapsam??ndaverilen
                cihaz veya cihazlar??n ??al??nmas??, abone kaynakl?? nedenlerden
                dolay?? bozulmas?? durumunda, kampanya nedeniyle kiral??k olarak
                verilencihazveya cihazlar??n ??creti, (varsa)??denmemi?? ba??lant??
                ??creti ve taksitleri, (varsa) geriye d??n??k kullan??m ??cretleri,
                (varsa) tarifede yeralandi??er kullan??mlara ait bor??lar?? aboneden
                tahsil edilecektir. Abone, abonelikten vazge??me taleplerini
                YAZILI olarak R??ZGARF??BER'eiletmekle y??k??ml??d??r.</li>
            <li>Sosyal medya (Facebook, Twitter, Instagram vb.) veya
                sikayetvar.com gibi t??ketici platformlar??nda, ticari itibar??m??z??
                etkileyecek as??ls??z yorumlar??n yap??lmas?? durumunda, aboneli??in
                tek tarafl?? fesh edilece??ini ve cayma bedeli(yerinde kurulum,
                port tahsis, tarife indirim, yarg?? ve tazmin bedelleri)ni pe??in
                olarak ??deyece??imi kabul ve taahh??t ederim.</li>
        </ol>
        <div>
            <table class="user_table">
                <thead>
                    <tr>
                        <th>Marka Model</th>
                        <th>Seri Numaras??</th>
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
                        <th>Yetkili Ad?? Soyad?? ??mza</th>
                        <th>M????teri Ad?? Soyad?? ??mza</th>
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
        <p class="title big-title">B??REYSEL TAR??FE, ??ND??R??M VE C??HAZ
            TAAHH??TNAMES?? EK?? - 1
        </p>
        <ul>
            <li>Bu kampanya kapsam??nda aboneye kablolu ya da kablosuz modem veya
                cihaz sat??lmayacakt??r. E??er abone cihaz al??rsa 24
                ayl??kaboneliklerde ADSL, VDSL modem kiralama bedeli 7 (yedi) TL,
                6-12 ayl??k aboneliklerde 9 (dokuz) TL'dir. Fiber modem ise t??m
                aboneliklerde13 (on ????) TL ayl??k faturas??na ek olarak
                ??cretlendirilecektir. Aboneli??in sona ermesine istinaden
                R??ZGARF??BER cihaz veya cihazlar?? sa??lamve ??al??????r bir ??ekilde
                iade alacakt??r. Aksi takdirde abone cihaz veya cihazlara ait
                olan (1500 TL)??creti ??demekle y??k??ml??d??r.</li>
            <li>R??ZGARF??BER, mevcut tarifelerinde, fiyat ve kampanya ??artlar??nda
                aboneyi 30 (otuz) g??n ??nceden bilgilendirmek ko??uluyla
                de??i??iklikyapma hakk??n?? sakl?? tutar.</li>
            <li>Se??ilen paketteki belirlenmi?? kampanya fiyat??na g??re
                faturaland??r??lma ??de-kullan ??eklindedir. Abone olunan tarihten,
                ilk fatura tarihinekadar ge??en s??redeki ??cret, kampanya
                dahilinde se??ilen paketin belirtilen indirimli internet hizmeti
                tarifesinden hesaplanarak faturaedilecektir. Abone, 6, 12, 24
                veya 27 ayl??k d??nem zarf??nda sadece kampanya kapsam??nda ge??i??e
                a????k olan paketlere, kampanyaya giri??tetercih etti??i tarife
                paketinin ??cret olarak alt??na inmemek ko??uluyla, h??z/paket
                de??i??ikli??i yapabilecektir. S??z konusu indirimler ayl??k
                kullan??m??cretlerini kapsamakta olup, abone taraf??ndan se??ilen
                tarifede yer alan di??er ??cretlerde herhangi bir indirim s??z
                konusu de??ildir. ??cretli katmade??erli servislere abone olunmas??
                durumunda, kullan??m bedelleri faturaya ayr??ca yans??t??l??r. ????bu
                kampanya taahh??tnamesinden kaynaklanandamga vergisi taahh??t
                s??resince taksitlere b??l??necektir, ve abonenin faturas??na
                yans??t??lacakt??r.</li>
            <li>????letmeci veya abone taraf??ndan, herhangi bir sebeple 6, 12, 24
                veya 27 ayl??k taahh??t s??resi tamamen dolmadan ??nce; abonenin
                dahiloldu??u kampanyan??n ve/veya aboneli??inin iptal edilmesi
                ve/veya kampanya giri?? paketinden daha d??????k ??cretli bir pakete
                ge??ilmesihalinde;taahh??tnamenin abone taraf??ndan imzaland????
                tarihten itibaren taahh??de ayk??r??l??????n olu??tu??u d??neme kadar
                aboneye sa??lananindirim, rayi?? cihaz veya di??er faydalar??n
                bedellerinin tahsil edilmemi?? k??sm??n??n toplam?? aboneden tahsil
                edilecektir Ancak aboneden taahh??tkapsam??ndatahsil edilece??i
                belirlenen hizmet bedellerinin hen??z tahakkuk etmemi?? k??sm??n??n
                toplam??, bu tutardan d??????k olmas halinde d??????kolan tutaraboneden
                tahsil edilecektir. Ek olarak (varsa)kurulum ??cretinin ve
                cihaz/servis/??r??nlerin kalan toplam?? ayr??ca tahsil edilir.</li>
            <li>Abone kampanya ko??ullar??na ve kampanya dahilindeki internet
                hizmet tarifeleri, ge??i?? ??creti vb. ile ilgili bilgilere 0850
                302 51 51 telefonnumaras??, www.ruzgarfiber.com.tr veya R??zgar
                Destek uygulamas?? ??zerinden ula??abilecektir.</li>
            <li>??r??n ve s??zle??meye ait kargo s??recinden kargo ve kurye ??irketi
                sorumludur. R??ZGARF??BER'in herhangi bir sorumlulu??u yoktur.</li>
            <li>Kiral??k verilen modem abonelik bitiminde teslim edilmek
                zorundad??r. Aksi takdirde modem i??in 1500 TL tahsil edilecektir.
            </li>
            <li>????bu taahh??tnamede yer almayan hususlarda abonelik s??zle??mesinin
                h??k??mleri ge??erlidir.</li>
            <li>Abone tarifesini de??i??tirirken sadece ??st paketleri tercih
                edebilir. Tarife y??kseltme i??lemini yapabilmesi i??in R??zgar
                Destek uygulamas??'Tarife Y??kselt' b??l??m??n?? kullanmal?? veya
                ??irket taraf??na yaz??l?? talimat g??ndermelidir. ????lem
                tamamland??ktan sonra faturalar yeni tarife ??creti??zerinden
                d??zenlenir. Fatura kesim tarihi her ay??n birinci g??n??d??r ve
                abonenin faturas??na ait ??deme, ilgili ay??n on be??inci g??n??nde
                belirledi??i??demey??ntemi(Kredi/Banka Kart??) ile
                ger??ekle??tirilecektir. ??demesi ger??ekle??tirilemeyen aboneye,
                d??rt g??n ek s??re verilecektir. Bu s??rei??erisinde hala ??deme
                al??namam???? ise hizmet ge??ici olarak durdurulacakt??r. Hizmetin
                durdurulmas??ndan sonra ger??ekle??tirilmi?? ??demelerdeabonenin, o
                ay faturas??na ek hizmet bedeli yans??t??lacakt??r.</li>
        </ul>
        <p>*Akarnet Telekom Sanayi Ticaret Limited ??irketi taraf??ndan a????klanan
            6698 say??l?? Ki??isel Verilerin Korunmas?? Kanunu'na ili??kin
            ayd??nlatmametninin www.ruzgarfiber.com.tr web sitesinde tamam??n??
            okudum, anlad??m ve Akarnet Telekom'un Ki??isel Verilerimi yukar??da
            belirtilenama??lar ??er??evesinde i??lemesi konusunda bilgilendirildim.
            Bu kapsamda Ki??isel Verilerimin 6698 say??l?? Ki??isel Verilerin
            Korunmas??Kanunu'na uygun olarak ??irketiniz Akarnet Telekom Ltd. ??ti.
            taraf??ndan, gerekli bilgilerin yasalar gere??ince muhafazas??, Akarnet
            Telekom'unM????terilerine ??r??n / hizmet sunmas??, tedarik??i ya da
            ??reticilerden ??r??n ve/veya hizmet tedariki sa??lamas?? ve/veya bu
            konuda s??zle??meli yada s??zle??mesiz ticari ili??kilerin kurulmas?? ve
            ifa edilmesi, ilgili belgeleri imzalayan taraflar??n tespiti ve
            kontrol??, bunlar kapsam??ndager??ekle??tirilecek her t??rl?? ba??vuru, i??
            ve i??lemin sahibini ve muhatab??n?? belirlemek ??zere bilgilerin
            tespiti i??in kimlik, adres, vergi numaras?? vedi??er bilgileri
            kaydetmek, k??????t ??zerinde veya elektronik ortamda ger??ekle??tirilecek
            i?? ve i??lemlere dayanak olacak bilgi ve belgelerid??zenlenmesi gibi
            ama??lar??n ger??ekle??tirilmesi i??in her t??rl?? kanallar arac??l??????yla
            Ayd??nlatma Metninde er alan bilgiler ??????????nda i??lenmesine vekanuni
            ya da hizmete ve/veya i?? ili??kisine ba??l?? fiili gereklilikler
            halinde Ayd??nlatma Metninde belirtilen ki??iler ile payla????lmas??na
            konu hakk??ndateredd??de yer vermeyecek ??ekilde ayd??nlat??lm???? ve bilgi
            sahibi olarak, a????k r??zamla onay veriyorum.*??irketinizden alm????
            bulundu??um hizmetten ya da hizmeti alan ki??iye kefil oldu??umdan
            dolay?? ayl??k kullan??m bedelimin a??a????da bilgilerinipayla??t??????m
            tutar?? belli olan mebla????n kredi/banka kart??mdan ??ekilmesine onay
            veriyorum. Alm???? oldu??um hizmetler dahilinde imzalad??????ms??zle??medeki
            ??artlar?? burada da ge??erlidir. Abonelik s??zle??mesi, Abonelik ??ndirim
            Taahh??tnamesi, Kiral??k Cihaz Taahh??tnamesiR??ZGARF??BER'e olu??an her
            t??rl?? bor?? tahsilat??m??n bu kredi/banka kart??ndan ??ekim yap??larak
            tahsil edilmesini onayl??yorum.R??ZGARF??BER(Akarnet Telekom ltd. ??ti.)
            s??zle??mem dahilinde faturaland??rd?????? tutar??n kredi/banka kart??mdan
            otomatik ??ekilmesine onayveriyorum. Kiral??k olarak alm???? oldu??um
            ??r??nlerin taahh??d??n?? bozmam, kaybetmem ya da hasarl?? teslim etmem
            halinde ??cretlerinin bilgilerinipayla??t??????m kredi kart??ndan
            ??ekilmesine onay veriyorum. Kredi/banka kart?? ekstrem de
            ??irketimizin ????z??m orta???? oldu??u R??zgarNET veyaMoka ??deme Kurulu??u
            olarak g??r??necektir.Taraf??ma bilgi verilmi??tir.</p>
        <ul>
            <li>Abone, 3D secure onay?? ile ilk aktivasyon s??ras??nda al??nan
                tarife tutar?? ve ??n provizyon i??in al??nan tarife bedelini veya
                ??n provizyon i??inal??nm???? olan bedeli ald?????? hizmet s??resince
                otomatik olarak ge??ilmesine onay vermi?? say??lacakt??r.</li>
            <li>Bu s??zle??meden do??an uyu??mazl??klarda Ni??de icra dairesi ve
                mahkemelerinin yetkisini kabul ediyorum.</li>
        </ul>
        <h5 class="title">KRED??/BANKA KARTI</h5>
        <div>
            <table class="user_table">
                <thead>
                    <tr>
                        <th>Ad?? Soyad??</th>
                        <th>Kart Numaras??</th>
                        <th colspan="2">Son Kullanma Tarihi</th>
                        <th>G??venlik Kodu</th>
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
        <p class="title big-title">TAAHH??TNAME</p>
        <p>Yukar??da belirtilen ve se??mi?? oldu??um kampanya ko??ullar??n??n okudu??umu
            ve aynen kabul etti??imi; internet hizmetine ili??kin olarak
            t??my??k??ml??l??klerimi zaman??nda ve tam olarak yerine getirece??imi,
            internet aboneli??imi sonland??rmaya karar verdi??imde veya
            R??ZGARF??BERtaraf??ndan teknik imk??ns??zl??klar nedeniyle hizmetin
            s??rekli olarak verilememesi durumunda veya R??ZGARF??BER taraf??ndan
            aboneli??ime sonverilmesi veya herhangi bir nedenle aboneli??imin son
            bulmas?? halinde, kampanya nedeniyle kampanyal??/indirimli s??re
            boyunca kiral??k olarakverilen cihaz veya cihazlar??n ??cretini,
            kullan??m ??cretinde yap??lan indirimlerin toplam??n??,(varsa) geriye
            d??n??k kullan??m ??cretlerini, (varsa)??denmemi?? ba??lant?? ??creti ve
            taksitleri, (varsa) tarifede yer alan di??er kullan??mlara ait
            bor??lar?? ilk talepte, nakden, defaten, herhangi birh??k??m ihdas??na
            gerek kalmaks??z??n R??ZGARF??BER'e ??demeyi gayr??kabili r??cu olarak
            kabul,beyan ve taahh??t ederim.</p>
        <div>
            <table class="table-signature">
                <thead>
                    <tr>
                        <th>Yetkili Ad?? Soyad?? ??mza</th>
                        <th>M????teri Ad?? Soyad?? ??mza</th>
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
