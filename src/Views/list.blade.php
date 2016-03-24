<table {!! $attrs !!}>

    <thead>
    <tr>
        @foreach($columns as $column)
            <th>{{ $column->getLabel() }}</th>
        @endforeach

        @if(count($contextActions) > 0)
            <th></th>
        @endif
    </tr>
    </thead>

    <tbody>
    @foreach($values as $row)
        <tr>
            @foreach($columns as $column)
                {{ $column->setContext($row) }}

                <td>@if(!$column->hidden()){!! $column->getValue() !!}@endif</td>
            @endforeach

            @if(count($contextActions) > 0)
                <td>
                    @foreach($contextActions as $action)
                        {{ $action->setContext($row) }}

                        @if(!$action->hidden())
                            <a href="{{ $action->url }}" class="btn btn-default">
                                <span class="{{ $action->icon }}"></span>
                                {!! $action->getLabel() !!}
                            </a>
                        @endif
                    @endforeach
                </td>
            @endif
        </tr>
    @endforeach
    </tbody>

    <tfoot>
    <tr>
        @foreach($columns as $column)
            <th>{{ $column->getLabel() }}</th>
        @endforeach
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