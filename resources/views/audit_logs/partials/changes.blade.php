@php
    $changes = $activity->attribute_changes ?? collect();

    $attributes = $changes instanceof \Illuminate\Support\Collection
        ? $changes->get('attributes', [])
        : ($changes['attributes'] ?? []);

    $oldValues = $changes instanceof \Illuminate\Support\Collection
        ? $changes->get('old', [])
        : ($changes['old'] ?? []);
@endphp

@if(!empty($attributes))

    <div class="table-responsive">
        <table class="table table-sm table-bordered align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 25%;">Field</th>
                    <th style="width: 37.5%;">Previous Value</th>
                    <th style="width: 37.5%;">New Value</th>
                </tr>
            </thead>

            <tbody>
                @foreach($attributes as $field => $newValue)
                    @php
                        $oldValue = $oldValues[$field] ?? null;

                        $formatValue = function ($value) {
                            if (is_null($value)) {
                                return '<span class="text-muted">null</span>';
                            }

                            if (is_bool($value)) {
                                return $value ? 'true' : 'false';
                            }

                            if (is_array($value) || is_object($value)) {
                                return e(json_encode(
                                    $value,
                                    JSON_PRETTY_PRINT |
                                    JSON_UNESCAPED_UNICODE |
                                    JSON_UNESCAPED_SLASHES
                                ));
                            }

                            return e((string) $value);
                        };
                    @endphp

                    <tr>
                        <td>
                            <code>{{ $field }}</code>
                        </td>

                        <td>
                            @if(is_array($oldValue) || is_object($oldValue))
                                <pre class="bg-light rounded p-2 small mb-0">{{ json_encode($oldValue, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
                            @else
                                {!! $formatValue($oldValue) !!}
                            @endif
                        </td>

                        <td>
                            @if(is_array($newValue) || is_object($newValue))
                                <pre class="bg-light rounded p-2 small mb-0">{{ json_encode($newValue, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
                            @else
                                {!! $formatValue($newValue) !!}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@else

    <div class="text-muted">
        No attribute changes were recorded for this activity.
    </div>

@endif