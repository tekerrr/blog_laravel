<div class="form-row align-items-center pt-2">
    <div class="col-6">
        <label for="{{ $name }}">{{ $label }}</label>
    </div>
    <div class="col-1">
        <input type="{{ $type ?? 'text' }}"
               name="{{ $name }}"
               id="{{ $name }}"
               class="form-control rounded-0 pl-3 @error($name) is-invalid @enderror"
               value="{{ old($name, $default ?? '') }}"
       >
        @include('layout.input.error', ['name' => $name])
    </div>
</div>
