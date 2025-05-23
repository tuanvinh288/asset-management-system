@extends('layouts.app')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>In QR Code</h4>
                    <span class="ml-1">In QR code cho {{ count($deviceItems) }} thiết bị</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <button type="button" class="btn btn-primary" onclick="window.print()">
                    <i class="fa fa-print"></i> In tất cả
                </button>
            </div>
        </div>

        <div class="row">
            @foreach($deviceItems as $item)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5>{{ $item->device->name }}</h5>
                            <p>Mã: {{ $item->code }}</p>
                            @if($item->qr_code)
                                <img src="{{ Storage::url($item->qr_code) }}" alt="QR Code" class="img-fluid" style="max-width: 200px;">
                            @else
                                <p>Chưa có QR Code</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
    @media print {
        .page-titles, .header, .footer, .nav-header {
            display: none !important;
        }
        .content-body {
            margin-left: 0 !important;
            padding: 0 !important;
        }
        .card {
            break-inside: avoid;
        }
    }
</style>
@endsection 