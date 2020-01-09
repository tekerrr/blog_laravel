<input type="checkbox" name="{{ $name ?? 'active' }}" data-toggle="toggle" {{ $checked ? 'checked' : '' }}
class="{{ $class ?? 'mx-auto' }}" data-onstyle="outline-primary" data-offstyle="outline-danger" data-style="squared" data-size="sm"
       onchange="form.submit()">
