@extends('layouts.app')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Tạo phiếu mượn phòng mới</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('room-borrows.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Phòng <span class="text-danger">*</span></label>
                                <select name="room_id" class="form-control @error('room_id') is-invalid @enderror" required>
                                    <option value="">Chọn phòng</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                            {{ $room->name }} ({{ $room->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('room_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Ngày mượn <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="borrow_date" class="form-control @error('borrow_date') is-invalid @enderror"
                                    value="{{ old('borrow_date') }}" required>
                                @error('borrow_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Ngày trả <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="return_date" class="form-control @error('return_date') is-invalid @enderror"
                                    value="{{ old('return_date') }}" required>
                                @error('return_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Lý do mượn <span class="text-danger">*</span></label>
                                <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" rows="3" required>{{ old('reason') }}</textarea>
                                @error('reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Tạo phiếu mượn</button>
                                <a href="{{ route('room-borrows.index') }}" class="btn btn-secondary">Hủy</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
