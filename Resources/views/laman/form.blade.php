<div class="box-typical-body padding-panel">
    <div class="row">
        <div class="col-md-12">

            <fieldset class="form-group {{ $errors->has('label')?'form-group-error':'' }}">
                <label for="label" class="form-label">
                    {{ __('laman::general.form.label.label') }} <span class="text-danger">*</span>
                </label>
                {!! Form::text('label', null, ['class' => 'form-control', 'placeholder' => 'Judul Laman']) !!}
                {!! $errors->first('label', '<span class="text-muted"><small>:message</small></span>') !!}
            </fieldset>

			{!! Form::textarea('content', null, ['class' => 'tinymce']) !!}
            {!! $errors->first('content', '<span class="text-muted text-danger"><small>:message</small></span>') !!}

        </div>
    </div>
</div>