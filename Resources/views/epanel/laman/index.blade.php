@extends('core::page.content')
@section('inner-title', "$title • ")
@section('mLaman', 'opened')

@section('css')
    @include('core::layouts.partials.datatables')
@endsection

@section('js') 
    <script src="https://cdn.enterwind.com/template/epanel/js/lib/datatables-net/datatables.min.js"></script>
    <script>
        $(function() {
            $('#datatable').DataTable({
                order: [[ 0, "asc" ]], 
                processing: true,
                serverSide: true,
                ajax : '{!! request()->fullUrl() !!}?datatable=true',
                columns: [
                    { data: 'pilihan', name: 'pilihan', className: 'table-check' },
                    { data: 'label', name: 'label' },
                    { data: 'status', name: 'status', className: 'text-left' },
                    { data: 'tanggal', name: 'tanggal', className: 'table-date small' },
                    { data: 'oleh', name: 'oleh', className: 'table-photo' },
                    { data: 'aksi', name: 'aksi', className: 'tombol' }
                ],
                "fnDrawCallback": function( oSettings ) {
                    @include('core::layouts.components.callback')
                }
            });
        });
        @include('core::layouts.components.hapus')
    </script>
@endsection

@section('content')

    @if(!$data->count())

        @include('core::layouts.components.kosong', [
            'icon' => 'font-icon font-icon-page',
            'judul' => $title,
            'subjudul' => __('laman::general.empty', ['message' => str_replace('Modul ', 'data ', $title)]), 
            'tambah' => route("$prefix.create")
        ])

        &bullet;

    @else
        
        {!! Form::open(['method' => 'delete', 'route' => ["$prefix.destroy", 'hapus-all'], 'id' => 'submit-all']) !!}

            @include('core::layouts.components.top', [
                'judul' => $title,
                'subjudul' => __('laman::general.desc'),
                'tambah' => route("$prefix.create"), 
                'hapus' => true
            ])

            <div class="card">
                <div class="card-block table-responsive">
                    <table id="datatable" class="display table table-striped" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="table-check"></th>
                                <th>{{ __('laman::general.table.page') }}</th>
                                <th width="10%">{{ __('laman::general.table.status') }}</th>
                                <th class="text-right">{{ __('laman::general.table.updated') }}</th>
                                <th class="table-photo"></th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            
        {!! Form::close() !!}
    @endif
@endsection
