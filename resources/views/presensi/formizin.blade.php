@extends('layouts.presensi')
@section('header')
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle"> Form Izin / Sakit</div>
    <div class="right"></div>
</div>
@endsection
@section('content')
<div class="row" style="margin-top:4rem">
    <div class="col">
        <form action="POST" action="#">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Tanggal">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

