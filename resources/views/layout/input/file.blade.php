<div class="form-group {{ $divClass ?? '' }}">
    <label for="{{ $name }}" class="text-primary {{ $labelClass ?? '' }}">{{ $label }}</label>
    <input type="file"
           name="{{ $name }}"
           id="{{ $name }}"
           class="form-control-file @error($name) is-invalid @enderror {{ $class ?? '' }}"
           {{ $addition ?? '' }}
           value="{{ old($name, $default ?? '') }}"
    >
    @include('layout.input.error', ['name' => $name])
</div>
