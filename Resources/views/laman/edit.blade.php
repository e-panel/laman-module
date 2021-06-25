@extends('core::page.content')
@section('inner-title', __('laman::general.edit.title', ['attribute' => $title]) . ' - ')
@section('mLaman', 'opened')

@section('js')
	@include('core::layouts.components.tinymce')
@endsection

@section('content')
	<section class="box-typical">

		{!! Form::model($edit, ['route' => ["$prefix.update", $edit->uuid], 'autocomplete' => 'off', 'method' => 'PUT']) !!}

	    	@include('core::layouts.components.top', [
                'judul' => __('laman::general.edit.title', ['attribute' => $title]),
                'subjudul' =>  __('laman::general.edit.desc'),
                'kembali' => route("$prefix.index")
            ])
	    
	        <div class="card">
                @include("$view.form")
                @include('core::layouts.components.submit')
            </div>

	    {!! Form::close() !!}

	</section>
@endsection