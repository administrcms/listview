<table {{ $attrs }}>

    <tr>
        @foreach($columns as $column)
            <th>{{ $column->getLabel() }}</th>
        @endforeach
    </tr>

    @foreach($values as $value)
        <tr>
        @foreach($columns as $column)
            <td>{{ $column->getValue($value[$column->getName()]) }}</td>
        @endforeach
        </tr>
    @endforeach

    <tr>
        @foreach($columns as $column)
            <th>{{ $column->getLabel() }}</th>
        @endforeach
    </tr>

</table>