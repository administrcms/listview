<a href="{{ $action->url }}" {!! $action->attributes() !!} class="btn btn-default">
    <span class="{{ $action->icon }}"></span>
    {!! $action->getLabel() !!}
</a>