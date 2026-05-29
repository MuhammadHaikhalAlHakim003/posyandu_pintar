@props(['columns', 'data', 'emptyMessage' => 'Tidak ada data'])

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                @foreach($columns as $column)
                    <th {{ isset($column['width']) ? 'width=' . $column['width'] : '' }}>
                        {{ $column['label'] }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($data as $row)
                <tr>
                    @foreach($columns as $column)
                        <td>
                            @if(isset($column['render']))
                                {!! $column['render']($row) !!}
                            @else
                                {{ data_get($row, $column['field']) }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columns) }}" class="text-center py-4">
                        @include('components.empty-state', ['message' => $emptyMessage])
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
