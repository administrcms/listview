<tr>
    @foreach($columns as $column)
        @if($column->visible())
            {{ $column->setContext($row) }}

            <td {!! $column->renderAttributes($column->getOptions()) !!}>
                @if($action->visible())
                    {!! $column->getValue() !!}
                @endif
            </td>
        @endif
    @endforeach
</tr>