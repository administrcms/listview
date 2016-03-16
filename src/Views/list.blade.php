<table {!! $attrs !!}>

    <thead>
        <tr>
            @foreach($columns as $column)
                <th>{{ $column->getLabel() }}</th>
            @endforeach
        </tr>
    </thead>

    <tbody>
        @foreach($values as $value)
            <tr>
                @foreach($columns as $column)
                    <td>{{ $column->getValue($value[$column->getName()]) }}</td>
                @endforeach
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
                <td colspan="{{ count($columns) }}">
                    {!! $paginationLinks !!}
                </td>
            </tr>
        @endif

    </tfoot>
</table>