@props(['name', 'label' => null])
<span {{ $attributes->class(['aui-icon']) }} @if($label !== null) role="img" aria-label="{{ $label }}" @else aria-hidden="true" @endif>{!! app(\Aestetic\Ui\Support\IconSet::class)->svg($name) !!}</span>
