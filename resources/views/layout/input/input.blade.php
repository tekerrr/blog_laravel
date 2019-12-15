<div class="form-group {{ $divClass ?? '' }}">
    <label for="{{ $name }}" class="text-primary {{ $labelClass ?? '' }}">{{ $label }}</label>
    <input type="{{ $type ?? 'text' }}"
           name="{{ $name }}"
           id="{{ $name }}"
           class="form-control rounded-0 @error($name) is-invalid @enderror {{ $class ?? '' }}"
           {{ $addition ?? '' }}
           value="{{ old($name, $default ?? '') }}"
    >
    @include('layout.input.error', ['name' => $name])
</div>
