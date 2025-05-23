@if ($errors->any())
    <div class="alert alert-dark solid"><strong>Lỗi!</strong>
        @foreach ($errors->all() as $error)
            <span>{{ $error }}</span>
        @endforeach
    </div>
@endif
