@extends('core::page.content')
@section('inner-title', __('laman::general.create.title', ['attribute' => $title]) . ' - ')
@section('mLaman', 'opened')

@section('js')
	@include('core::layouts.components.tinymce')
@endsection

@section('content')
	<section class="box-typical">
		
		{!! Form::open(['route' => "$prefix.store", 'autocomplete' => 'off']) !!}

	    	@include('core::layouts.components.top', [
                'judul' => __('laman::general.create.title', ['attribute' => $title]),
                'subjudul' =>  __('laman::general.create.desc'),
                'kembali' => route("$prefix.index")
            ])
	    
	        <div class="card">
                @include("$view.form")
                @include('core::layouts.components.submit')
            </div>

	    {!! Form::close() !!}

	</section>
@endsection