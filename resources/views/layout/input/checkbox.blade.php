<div class="form-check {{ $divClass ?? '' }}">
    <input type="checkbox"
           name="{{ $name }}"
           id="{{ $name }}"
           class="form-check-input @error($name) is-invalid @enderror {{ $class ?? '' }}"
           {{ $addition ?? '' }}
           {{ (old($name) || (! old('_token') && ($default ?? ''))) ? 'checked' : '' }}
    >
    <label for="{{ $name }}" class="form-check-label text-primary {{ $labelClass ?? '' }}">{{ $label }}</label>
    @include('layout.input.error', ['name' => $name])
</div>
