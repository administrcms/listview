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
        <tr>
            @foreach($columns as $column)
                @if($column->visible())
                    {{ $column->setContext($row) }}

                    <td {!! $column->renderAttributes($column->getOptions()) !!}>
                        {!! $column->getValue() !!}
                    </td>
                @endif
            @endforeach

            @if(count($contextActions) > 0)
                <td>
                    @foreach($contextActions as $action)
                        {{ $action->setContext($row) }}

                        @if($action->visible())
                            <a href="{{ $action->url }}" {!! $action->renderAttributes($action->getOptions()) !!} class="btn btn-default">
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