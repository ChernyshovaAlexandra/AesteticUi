<form method="GET" action="{{ url()->current() }}" class="wb-example-actions">
    <input type="hidden" name="theme" value="{{ $theme }}">
    <input type="hidden" name="density" value="{{ $density }}">
    <x-ui::button type="submit">Обновить пример</x-ui::button>
</form>
