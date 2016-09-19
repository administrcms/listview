<table {!! $attrs !!}>

    <thead>
    <tr>
        @foreach($columns as $column)
            <th>
                @if($column->isSortable())
                    <a href="{{ $column->sortLink() }}">{{ $column->getLabel() }}  <span class="fa fa-{{ $column->sortDirection() }}"></span></a>
                @else
                    {{ $column->getLabel() }}
                @endif
            </th>
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

                <td {!! $column->renderAttributes($column->getOptions()) !!}>
                    @if($column->visible()){!! $column->getValue() !!}@endif
                </td>
            @endforeach

            @if(count($contextActions) > 0)
                <td>
                    @foreach($contextActions as $action)
                        {{ $action->setContext($row) }}

                        @if(!$action->hidden())
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
            <th>{{ $column->getLabel() }}</th>
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