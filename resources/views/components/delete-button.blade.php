@props(
[
'url',
'class',
'label',
]
)


<a href="{{ $url }}" class="btn  {{ $class }}">
    <i class="fa fa-trash"></i>
    {{ $label }}
</a>