{!! $filters->render() !!}

<table {!! $attrs !!}>

    <thead>
        <tr>
            @foreach($columns as $column)
                @if($column->visible())
                    @include('administr/listview::_column')
                @endif
            @endforeach
        </tr>
    </thead>

    <tbody>
        @forelse($values as $row)
            @include('administr/listview::_row')
        @empty
            <td colspan="{{ count($columns) }}">{{ config('administr.listview.empty') }}</td>
        @endforelse
    </tbody>

    <tfoot>
        <tr>
            @foreach($columns as $column)
                @if($column->visible())
                    @include('administr/listview::_column')
                @endif
            @endforeach
        </tr>

        @if($paginationLinks)
            <tr>
                <td colspan="{{ count($columns) }}">
                    {!! $paginationLinks !!}
                </td>
            </tr>
        @endif
    </tfoot>
</table>