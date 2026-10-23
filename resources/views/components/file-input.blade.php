@props(['disabled' => false, 'multiple' => false, 'invalid' => false])
<input type="file" {{ $attributes->class(['aui-file-input'])->merge(['aria-invalid' => $invalid ? 'true' : null]) }} @disabled($disabled) @if($multiple) multiple @endif>
