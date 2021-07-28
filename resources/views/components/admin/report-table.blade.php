@foreach ($reports as $type => $report)
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4>@lang("report.{$type}.title") @if ($categoryKey) ({{ $categoryKey }}) @endif</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tbody>
                            @foreach (array_chunk($report, 2, true) as $chunk)
                                <tr>
                                    @foreach ($chunk as $key => $value)
                                        @if (in_array($type, ['types', 'type_counts']))
                                            <td>@lang("tables.payment.types.{$key}")</td>
                                        @elseif ($type == 'subscriptions')
                                            <td>
                                                @if (is_numeric(trans("report.{$type}.status.{$key}")))
                                                    <a target="_blank" href="{{ route('admin.subscriptions', ['status' => trans("report.{$type}.status.{$key}") ?? null]) }}">
                                                        @lang("report.{$type}.{$key}")
                                                    </a>
                                                @else
                                                    @lang("report.{$type}.{$key}")
                                                @endif
                                            </td>
                                        @elseif (in_array($key, ['penalty', 'penalty_paided', 'penalty_not_paided']))
                                            <td>
                                                <a target="_blank" href="{{ route('admin.payment.penalties') }}">
                                                    @lang("report.{$type}.{$key}")
                                                </a>
                                            </td>
                                        @else
                                            <td>@lang("report.{$type}.{$key}")</td>
                                        @endif

                                        @if (in_array($type, ['totals', 'types']))
                                            <td>{{ print_money($value) }}</td>
                                        @else
                                            <td>{{ $value }}</td>
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endforeach
