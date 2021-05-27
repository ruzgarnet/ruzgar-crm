@extends('admin.layout.main')

@section('title', meta_title('tables.subscription.add'))

@section('content')
    <div class="row">
        <div class="col-12">
            <form method="POST" action="{{ relative_route('admin.subscription.add.post') }}">
                <div class="card form">
                    <div class="card-header">
                        <h4>@lang('tables.subscription.add')</h4>

                        <div class="card-header-buttons">
                            <a href="{{ route('admin.subscriptions') }}" class="btn btn-primary"><i
                                    class="fas fa-sm fa-list-ul"></i> @lang('tables.subscription.title')</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="slcService">@lang('fields.service')</label>
                            <select name="service_id" id="slcService" class="custom-select service-select selectpicker"
                                v-model="service" v-selectpicker="service" v-on:change="changeService()">
                                <option selected disabled value="0">@lang('tables.service.select')</option>
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}">
                                        {{ $service->select_print }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="slcCustomer">@lang('fields.customer')</label>
                            <select name="customer_id" id="slcCustomer" class="custom-select selectpicker">
                                <option selected disabled>@lang('tables.customer.select')</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="inpPrice">@lang('fields.price')</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">₺</div>
                                        </div>
                                        <input type="number" v-model="price" name="price" id="inpPrice" class="form-control money-input"
                                            min="0" step=".01">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="inpBBKCode">@lang('fields.bbk_code')</label>
                                    <input type="text" name="bbk_code" id="inpBBKCode" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="slcCommitment">@lang('fields.commitment_period')</label>
                                    <select name="commitment" id="slcCommitment" class="custom-select" v-model="duration"
                                        :disabled="!hasOption('commitments')" v-select="">
                                        <template v-if="hasOption('commitments')">
                                            <option v-for="option in options.commitments" :value="option.value"
                                                v-text="option.title"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="inpStartDate">@lang('fields.start_date')</label>
                                    <input type="text" name="start_date" id="inpStartDate" class="form-control date-mask"
                                        v-model="startDate" value="{{ convert_date(date('Y-m-d'), 'mask') }}">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="inpEndDate">@lang('fields.end_date')</label>
                                    <input type="text" name="end_date" id="inpEndDate" class="form-control date-mask"
                                        :value="getEndDate" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6" v-if="hasOption('modems')">
                                <div class="form-group">
                                    <label for="slcModem">@lang('fields.modem')</label>
                                    <select name="options[modem]" id="slcModem" class="custom-select" v-model="modem"
                                        v-select="">
                                        <option v-for="option in options.modems" :value="option.value"
                                            v-text="option.title">
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6" v-if="hasOption('modem_serial') && (modem == 2 || modem == 3)">
                                <div class="form-group">
                                    <label for="inpModemSerial">@lang('fields.modem_serial')</label>
                                    <input type="text" name="options[modem_serial]" id="inpModemSerial"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="w-100"></div>
                            <div class="col-lg-6" v-if="hasOption('setup_payments')">
                                <div class="form-group">
                                    <label for="slcSetupPayment">@lang('fields.setup_payment')</label>
                                    <select name="options[setup_payment]" id="slcSetupPayment" class="custom-select"
                                        v-select="">
                                        <option v-for="option in options.setup_payments" :value="option.value"
                                            v-text="option.title">
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6" v-if="hasOption('pre_payment')">
                                <div class="form-group">
                                    <label for="chkPrePayment">@lang('fields.payment_type')</label>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="chkPrePayment"
                                            name="options[pre_payment]" value="1">
                                        <label class="custom-control-label"
                                            for="chkPrePayment">@lang('fields.pre_payment')</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6" v-if="hasOption('campaing_payments')">
                                <div class="form-group">
                                    <label for="slcCampaingPayment">@lang('fields.campaing_payment')</label>
                                    <select name="options[campaing_payment]" id="slcCampaingPayment" class="custom-select"
                                        v-select="">
                                        <option v-for="option in options.campaing_payments" :value="option.value"
                                            v-text="option.title">
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">@lang('fields.send')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('style')
    <link rel="stylesheet" href="/assets/admin/vendor/select2/css/select2.min.css">
@endpush

@push('script')
    <script src="/assets/admin/vendor/ckeditor/ckeditor.js"></script>
    <script src="/assets/admin/vendor/slugify/slugify.js"></script>
    <script src="/assets/admin/vendor/select2/js/select2.min.js"></script>
    <script src="/assets/admin/vendor/cleave/cleave.min.js"></script>
    <script src="/assets/admin/vendor/vue/vue.js"></script>

    <script>
        Vue.directive('selectpicker', {
            twoWay: true,
            bind: function(el, binding, vnode) {
                $(el).select2().on("select2:select", (e) => {
                    // v-model looks for
                    //  - an event named "change"
                    //  - a value with property path "$event.target.value"
                    el.dispatchEvent(new Event('change', {
                        target: e.target
                    }));
                });
            },
        });

        Vue.directive('select', {
            twoWay: true,
            bind: function(el, binding, vnode) {
                $(el).on("change", (e) => {
                    let select = el;
                    for (let option in select.options) {
                        select.options.item(option).removeAttribute("selected");
                    }
                    select.options
                        .item(select.selectedIndex)
                        .setAttribute("selected", true);
                });
            },
        });

        const app = new Vue({
            el: '#app',
            data: {
                price: null,
                service: 0,
                services: @json($service_props),
                fields: @json($option_fields),
                options: null,
                category: null,
                startDate: '{{ convert_date(date('Y-m-d'), 'mask') }}',
                duration: 0,
                modem: 0
            },
            methods: {
                changeService: function() {
                    this.category = this.services[this.service].category;
                    this.options = this.fields[this.category];
                    this.price = this.services[this.service].price;

                    if (this.hasOption('commitments')) {
                        this.duration = this.options.commitments[0].value;
                    }

                    if (this.hasOption('modems')) {
                        this.modem = this.options.modems[0].value;
                    }
                },
                hasOption: function(key) {
                    for (let option in this.options) {
                        if (option === key) {
                            return true;
                        }
                    }
                    return false;
                }
            },
            computed: {
                getStartDate() {
                    return this.startDate.toString().replace(/(\d*)[\/](\d*)[\/](\d*)/g,
                        '$3-$2-$1');
                },
                getEndDate() {
                    let date = new Date(this.getStartDate),
                        end_date = new Date(date.setMonth(date.getMonth() + this.duration));

                    if (!isNaN(date.getTime())) {
                        return moment(end_date).format('DD/MM/YYYY');
                    }
                    return '';
                }
            }
        })

    </script>
@endpush
