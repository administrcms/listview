<table {!! $attrs !!}>

    <thead>
        <tr>
            @foreach($columns as $column)
                <th>{{ $column->getLabel() }}</th>
            @endforeach
        </tr>
        @if(count($contextActions) > 0)
            <tr>
                <th></th>
            </tr>
        @endif
    </thead>

    <tbody>
        @foreach($values as $value)
            <tr>
                @foreach($columns as $column)
                    <td>{{ $column->getValue($value[$column->getName()]) }}</td>
                @endforeach
            </tr>
        @endforeach
        @if(count($contextActions) > 0)
            <tr>
                <td>
                    @foreach($contextActions as $action)
                        <a href="{{ $action->url }}" class="btn btn-default">
                            <span class="{{ $action->icon }}"></span>
                            {{ $action->getLabel() }}
                        </a>
                    @endforeach
                </td>
            </tr>
        @endif
    </tbody>

    <tfoot>
        <tr>
            @foreach($columns as $column)
                <th>{{ $column->getLabel() }}</th>
            @endforeach
        </tr>

        @if($paginationLinks)
            <tr>
                <td colspan="{{ count($columns) + count($contextActions) }}">
                    {!! $paginationLinks !!}
                </td>
            </tr>
        @endif

    </tfoot>
</table>