<div class="form-group">
    <label for="{{ $name }}" class="text-primary">{{ $label }}</label>
    <input type="{{ $type ?? 'text' }}"
           name="{{ $name }}"
           id="{{ $name }}"
           class="form-control rounded-0 @error($name) is-invalid @enderror"
           {{ $addition ?? '' }}
           value="{{ old($name, $default ?? '') }}"
    >
    @include('layout.input.error', ['name' => $name])
</div>
