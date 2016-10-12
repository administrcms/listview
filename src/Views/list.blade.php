{!! $filters->render() !!}

<table {!! $attrs !!}>

    <thead>
    <tr>
        @foreach($columns as $column)
            @if($column->visible())
                @include('administr/listview::_column')
            @endif
        @endforeach

        @if(count($contextActions) > 0)
            <th></th>
        @endif
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

        @if(count($contextActions) > 0)
            <th></th>
        @endif
    </tr>

    @if($paginationLinks)
        <tr>
            <td colspan="@if(count($contextActions) > 0){{ count($columns) + 1 }}@else{{ count($columns) }}@endif">
                {!! $paginationLinks !!}
            </td>
        </tr>
    @endif

    </tfoot>
</table>