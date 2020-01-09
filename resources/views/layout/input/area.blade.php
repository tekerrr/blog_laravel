<div class="form-group {{ $divClass ?? '' }}">
    <label for="{{ $name }}" class="text-primary {{ $labelClass ?? '' }}">{{ $label }}</label>
    <textarea name="{{ $name }}"
              id="{{ $name }}"
              class="form-control rounded-0 @error('body') is-invalid @enderror {{ $class ?? '' }}"
              rows="{{ $rows ?? 3 }}"
    >{{ old($name , $default ?? '') }}</textarea>
    @include('layout.input.error', ['name' => $name])
</div>
