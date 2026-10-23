@props(['value' => null, 'invalid' => false, 'rows' => 4])
<textarea rows="{{ $rows }}" {{ $attributes->class(['aui-textarea'])->merge(['aria-invalid' => $invalid ? 'true' : null]) }}>{{ $value ?? $slot }}</textarea>
