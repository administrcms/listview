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
        @foreach($values as $row)
            @include('administr/listview::_row')
        @endforeach
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