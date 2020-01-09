@if(session()->has('message'))
    <div class="container">
        <div class="row justify-content-center">
            <div class="w-75 alert alert-{{ session('message_type') }} mt-4">
                {{ session('message') }}
            </div>
        </div>
    </div>
@endif
