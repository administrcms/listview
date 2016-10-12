<th>
    @if($column->sortable())
        <a href="{{ $column->sortLink() }}">{{ $column->getLabel() }}  <span class="fa fa-{{ $column->sortDirection() }}"></span></a>
    @else
        {{ $column->getLabel() }}
    @endif
</th>