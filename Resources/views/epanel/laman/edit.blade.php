@extends('core::page.content')
@section('inner-title')
    {{ __('laman::general.edit.title', ['attribute' => str_replace('Modul ', '', $title)]) }}
@stop

@section('mLaman') opened @stop

@section('css')
@stop

@section('js')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.7.0/tinymce.min.js"></script>
	<script>
		var editor_config = {
			path_absolute : "/",
			selector: 'textarea.tinymce',
			relative_urls: false,
			height: 350,
			plugins: [
			"advlist autolink lists link image charmap print preview hr anchor pagebreak",
			"searchreplace wordcount visualblocks visualchars code fullscreen",
			"insertdatetime media nonbreaking save table directionality",
			"emoticons template paste textpattern"
			],
			toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
			file_picker_callback : function(callback, value, meta) {
				var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
				var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

				var cmsURL = editor_config.path_absolute + 'file-manager?editor=' + meta.fieldname;
				if (meta.filetype == 'image') {
					cmsURL = cmsURL + "&type=Images";
				} else {
					cmsURL = cmsURL + "&type=Files";
				}

				tinyMCE.activeEditor.windowManager.openUrl({
					url : cmsURL,
					title : 'Filemanager',
					width : x * 0.8,
					height : y * 0.8,
					resizable : "yes",
					close_previous : "no",
					onMessage: (api, message) => {
						callback(message.content);
					}
				});
			}
		};

		tinymce.init(editor_config);
	</script>
@stop

@section('content')
	<section class="box-typical">

		{!! Form::model($edit, ['route' => ["$prefix.update", $edit->id], 'autocomplete' => 'off', 'files' => true, 'method' => 'PUT']) !!}

	    	@include('core::layouts.components.top', [
                'judul' => __('laman::general.edit.title', ['attribute' => str_replace('Modul ', '', $title)]),
                'subjudul' =>  __('laman::general.edit.desc'),
                'kembali' => route("$prefix.index")
            ])
	    
	        <div class="card">
                @include("$view.form")
                @include('core::layouts.components.submit')
            </div>

	    {!! Form::close() !!}

	</section>
@stop