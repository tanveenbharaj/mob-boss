@extends('layouts.master')

@section('content')
    <div >
        <oard {{ isset($slug) ? "slug=$slug" : '' }}></oard>
    </div>
@stop

