@extends('admin.layout.main')

@section('title', meta_title('tables.subscription.edit'))

@section('content')
    <div class="row">
        <div class="col-12">
            <form method="POST" action="{{ relative_route('admin.subscription.edit.put', $subscription) }}">
                @method('put')
                <div class="card form">
                    <div class="card-header">
                        <h4>@lang('tables.subscription.edit') [{{ $subscription->id }}]</h4>

                        <div class="card-header-buttons">
                            <a href="{{ route('admin.subscriptions') }}" class="btn btn-primary"><i
                                    class="fas fa-sm fa-list-ul"></i> @lang('tables.subscription.title')</a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($subscription->approved_at)
                            <div class="alert alert-danger">
                                @lang('warnings.approved.subscription')
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="slcService">@lang('fields.service')</label>
                            <select name="service_id" id="slcService" class="custom-select service-select selectpicker"
                                v-model="service" v-selectpicker="service" v-on:change="changeService()">
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}" @if ($subscription->service_id == $service->id) selected @endif>
                                        {{ $service->select_print }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="slcCustomer">@lang('fields.customer')</label>
                            <select name="customer_id" id="slcCustomer" class="custom-select selectpicker"
                                v-model="customer">
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" @if ($subscription->customer_id == $customer->id) selected @endif>{{ $customer->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="txtAddress">@lang('fields.address')</label>
                            <textarea name="options[address]" id="txtAddress" rows="2"
                                placeholder="@lang('fields.subscription_address_placeholder')" class="form-control"
                                v-model="address"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="inpPrice">@lang('fields.price')</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">???</div>
                                        </div>
                                        <input type="number" v-model="price" name="price" id="inpPrice"
                                            class="form-control money-input" min="0" step=".01">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
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
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="inpStartDate">@lang('fields.start_date')</label>
                                    <input type="text" name="start_date" id="inpStartDate" class="form-control date-mask"
                                        v-model="startDate">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="inpEndDate">@lang('fields.end_date')</label>
                                    <input type="text" name="end_date" id="inpEndDate" class="form-control date-mask"
                                        :value="getEndDate" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4" v-if="hasOption('modems')">
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
                            <div class="col-lg-4" v-if="hasOption('modem_price') && modem != 1">
                                <div class="form-group">
                                    <label for="inpModemPrice">@lang('fields.modem_price')</label>
                                    <input type="number" name="options[modem_price]" v-model="modem_price"
                                        id="inpModemPrice" step="0.01" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="inpBBKCode">@lang('fields.bbk_code')</label>
                                    <input type="text" name="bbk_code" id="inpBBKCode" class="form-control"
                                        value="{{ $subscription->bbk_code }}">
                                </div>
                            </div>
                            <div class="w-100"></div>
                            <div class="col-lg-4" v-if="hasOption('modem_model') && modem != 1 && modem != 4">
                                <div class="form-group">
                                    <label for="slcModemModel">@lang('fields.modem_model')</label>
                                    <select name="options[modem_model]" id="slcModemModel" class="custom-select" v-select=""
                                        v-model="modem_model">
                                        <option v-for="option in options.modem_model" :value="option.value"
                                            v-text="option.title">
                                        </option>
                                    </select>
                                </div>
                            </div>
                            @for($i = 0; $i < 3; $i++)
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="inpModemModel">@lang('fields.devices.modem_brand')</label>
                                        <input type="text" name="options[devices][modem_brand][{{ $i }}]" id="inpModemModel"
                                            class="form-control" value="@isset($devices['modem_brand'][$i]) {{ $devices['modem_brand'][$i] }} @endisset">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="inpModemModel">@lang('fields.devices.modem_serial')</label>
                                        <input type="text" name="options[devices][modem_serial][{{ $i }}]" id="inpModemModel"
                                            class="form-control" value="@isset($devices['modem_serial'][$i]) {{ $devices['modem_serial'][$i] }}  @endisset">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="inpModemModel">@lang('fields.devices.modem_model')</label>
                                        <input type="text" name="options[devices][modem_model][{{ $i }}]" id="inpModemModel"
                                            class="form-control" value="@isset($devices['modem_model'][$i]) {{ $devices['modem_model'][$i] }} @endisset">
                                    </div>
                                </div>
                            @endfor
                            <div class="w-100"></div>
                            <div class="col-lg-4" v-if="hasOption('setup_payments')">
                                <div class="form-group">
                                    <label for="slcSetupPayment">@lang('fields.setup_payment', ['price' =>
                                        print_money(setting('service.setup.payment'))])</label>
                                    <select name="options[setup_payment]" id="slcSetupPayment" class="custom-select"
                                        v-model="setup_payment" v-select="">
                                        <option v-for="option in options.setup_payments" :value="option.value"
                                            v-text="option.title">
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4" v-if="hasOption('pre_payment')">
                                <div class="form-group">
                                    <label for="chkPrePayment">@lang('fields.payment_type')</label>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="chkPrePayment"
                                            name="options[pre_payment]" value="1" v-model="pre_payment">
                                        <label class="custom-control-label"
                                            for="chkPrePayment">@lang('fields.pre_payment')</label>
                                    </div>
                                    <div>(??de Kullan)</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="slcReferences">@lang('fields.reference')</label>
                            <select name="reference_id" id="slcReferences" class="custom-select selectpicker">
                                <option value="">@lang('tables.reference.select')</option>
                                @foreach ($subscriptions as $referenceSubscriptions)
                                    <option value="{{ $referenceSubscriptions->id }}" 
                                        @if ($subscription->getReferencedId() == $referenceSubscriptions->id) selected @endif
                                        >{{ $referenceSubscriptions->reference_print }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        @if ($subscription->approved_at == null)
                            <button type="submit" class="btn btn-primary">@lang('fields.send')</button>
                        @endif
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
    <script src="/assets/admin/vendor/slugify/slugify.js"></script>
    <script src="/assets/admin/vendor/select2/js/select2.min.js"></script>
    <script src="/assets/admin/vendor/cleave/cleave.min.js"></script>
    <script src="/assets/admin/vendor/vue/vue.min.js"></script>

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
                modem_price: {{ $subscription->options['modem_price'] ?? 12.99 }},
                price: {{ $subscription->price }},
                service: {{ $subscription->service_id }},
                services: @json($service_props),
                customer: {{ $subscription->customer_id }},
                fields: @json($option_fields),
                options: @json($subscription->service->category->option_fields),
                category: '{{ $subscription->service->category->key }}',
                startDate: '{{ convert_date($subscription->start_date, 'mask') }}',
                duration: {{ $subscription->commitment }},
                modem: {{ $subscription->options['modem'] ?? 1 }},
                setup_payment: {{ $subscription->options['setup_payment'] ?? 0 }},
                modem_serial: '{{ $subscription->options['modem_serial'] ?? '' }}',
                pre_payment: {{ $subscription->options['pre_payment'] ?? 0 }},
                modem_model: {{ $subscription->options['modem_model'] ?? 0 }},
                address: '{{ $subscription->options['address'] ?? '' }}'
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

                    if (this.hasOption('modem_model')) {
                        this.modem = this.options.modem_model[0].value;
                    }
                },
                hasOption: function(key) {
                    for (let option in this.options) {
                        if (option == key) {
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
            },
            mounted: function() {
                let selects = document.querySelectorAll("select");
                if (selects) {
                    selects.forEach(function(select, index) {
                        let selectedIndex = select.selectedIndex;
                        for (let option in select.options) {
                            select.options.item(option).removeAttribute("selected");
                        }
                        if (select.options.item(selectedIndex)) {
                            select.options
                                .item(selectedIndex)
                                .setAttribute("selected", true);
                        }
                    })
                }

                // Change selected options and trigger event
                $('#slcService').val({{ $subscription->service_id }});
                $('#slcService').trigger('change');

                // Change selected options and trigger event
                $('#slcCustomer').val({{ $subscription->customer_id }});
                $('#slcCustomer').trigger('change');
            }
        })
    </script>
@endpush
