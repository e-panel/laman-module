<?php

/* Author : Noviyanto Rahmadi 
 * E-mail : novay@btekno.id
 * Copyright 2020 Borneo Teknomedia. */

namespace Modules\Laman\Http\Controllers;

use Modules\Core\Http\Controllers\CoreController as Controller;
use Illuminate\Http\Request;

use Modules\Laman\Entities\Laman;
use Modules\Laman\Http\Requests\LamanRequest;

class LamanController extends Controller
{
    protected $title;

    /**
     * Siapkan konstruktor controller
     * 
     * @param Laman $data
     */
    public function __construct(Laman $data) 
    {
        $this->title = __('laman::general.title');
        
        $this->middleware('auth');
        $this->data = $data;

        $this->toIndex = route('epanel.laman.index');
        $this->prefix = 'epanel.laman';
        $this->view = 'laman::laman';

        $this->tCreate = __('laman::general.created', ['title' => $this->title]);
        $this->tUpdate = __('laman::general.updated', ['title' => $this->title]);
        $this->tDelete = __('laman::general.deleted', ['title' => $this->title]);

        view()->share([
            'title' => $this->title, 
            'view' => $this->view, 
            'prefix' => $this->prefix
        ]);
    }

    /**
     * Tampilkan halaman utama modul yang dipilih
     * 
     * @param Request $request
     * @return Response|View
     */
    public function index(Request $request) 
    {
        $data = $this->data->latest()->get();

        if($request->has('datatable')):
            return $this->datatable($data);
        endif;

        return view("$this->view.index", compact('data'));
    }

    /**
     * Tampilkan halaman untuk menambah data
     * 
     * @return Response|View
     */
    public function create() 
    {
        return view("$this->view.create");
    }

    /**
     * Lakukan penyimpanan data ke database
     * 
     * @param Request $request
     * @return Response|View
     */
    public function store(LamanRequest $request) 
    {
        $data = $request->all();

        $data['uuid'] = uuid();
        $data['slug'] = str_slug($request->label);
        $data['id_admin'] = optional(auth()->user())->id;

        $this->data->create($data);

        notify()->flash($this->tCreate, 'success');
        return redirect($this->toIndex);
    }

    /**
     * Menampilkan detail lengkap
     * 
     * @param Int $id
     * @return Response|View
     */
    public function show($id)
    {
        return abort(404);
    }

    /**
     * Tampilkan halaman perubahan data
     * 
     * @param Int $id
     * @return Response|View
     */
    public function edit(Request $request, $id)
    {
        $edit = $this->data->uuid($id)->firstOrFail();

        if($request->has('profil')):
            $edit->update(['profil' => $edit->profil == 0 ? 1 : 0]);
            notify()->flash(__('laman::general.changed'), 'success');
            return redirect()->back();
        endif;
    
        return view("$this->view.edit", compact('edit'));
    }

    /**
     * Lakukan perubahan data sesuai dengan data yang diedit
     * 
     * @param Request $request
     * @param Int $id
     * @return Response|View
     */
    public function update(LamanRequest $request, $id)
    {    
        $edit = $this->data->uuid($id)->firstOrFail();

        $data = $request->all();
        $data['slug'] = str_slug($request->label);

        $edit->update($data);

        notify()->flash($this->tUpdate, 'success');
        return redirect($this->toIndex);
    }

    /**
     * Lakukan penghapusan data yang tidak diinginkan
     * 
     * @param Request $request
     * @param Int $id
     * @return Response|String
     */
    public function destroy(Request $request, $id)
    {
        if($request->has('pilihan')):
            foreach($request->pilihan as $temp):
                $each = $this->data->uuid($temp)->firstOrFail();
                $each->delete();
            endforeach;
            notify()->flash($this->tDelete, 'success');
            return redirect()->back();
        endif;
        $satu = $this->data->uuid($id)->first();
        $satu->delete();
        return 'success';
    }

    /**
     * Datatable API
     * 
     * @param  $data
     * @return Datatable
     */
    public function datatable($data) 
    {
        return datatables()->of($data)
            ->editColumn('pilihan', function($data) {
                $return  = '<span>';
                $return .= '    <div class="checkbox checkbox-only">';
                $return .= '        <input type="checkbox" id="pilihan['.$data->id.']" name="pilihan[]" value="'.$data->uuid.'">';
                $return .= '        <label for="pilihan['.$data->id.']"></label>';
                $return .= '    </div>';
                $return .= '</span>';
                return $return;
            })
            ->editColumn('label', function($data) {
                $return  = $data->label;
                $return .= '<div class="font-11 color-blue-grey-lighter">';
                $return .= '    <i class="fa fa-link"></i> <a href="'.route('frontend.laman.index', $data->slug).'" target="_blank"><b>Visit Link</b></a>';
                $return .= '</div>';
                return $return;
            })
            ->editColumn('profil', function($data) {
                $return  = '<div class="btn-toolbar">';
                if($data->profil == 1):
                    $return .= '    <div class="btn-group btn-group-sm">';
                    $return .= '        <a href="'. route("$this->prefix.edit", $data->uuid) .'?profil=true" class="btn btn-sm btn-success">';
                    $return .= '            <span class="fa fa-check"></span>';
                    $return .= '        </a>';
                    $return .= '    </div>';
                    $return .= '</div>';
                else:
                    $return .= '    <div class="btn-group btn-group-sm">';
                    $return .= '        <a href="'. route("$this->prefix.edit", $data->uuid) .'?profil=true" class="btn btn-sm btn-danger">';
                    $return .= '            <span class="fa fa-times"></span>';
                    $return .= '        </a>';
                    $return .= '    </div>';
                    $return .= '</div>';
                endif;
                return $return;
            })
            ->editColumn('tanggal', function($data) {
                \Carbon\Carbon::setLocale('id');
                $return  = '<small>' . date('Y-m-d h:i:s', strtotime($data->created_at)) . '</small><br/>';
                if($data->updated_at):
                    $return .= str_replace('yang ', '', $data->updated_at->diffForHumans());
                endif;
                return $return;
            })
            ->editColumn('aksi', function($data) {
                $return  = '<div class="btn-toolbar">';
                $return .= '    <div class="btn-group btn-group-sm">';
                $return .= '        <a href="'. route("$this->prefix.edit", $data->uuid) .'" class="btn btn-sm btn-primary-outline">';
                $return .= '            <span class="fa fa-pencil"></span>';
                $return .= '        </a>';
                $return .= '    </div>';
                $return .= '</div>';
                return $return;
            })
            ->rawColumns(['pilihan', 'label', 'tanggal', 'profil', 'aksi'])->toJson();
    }
}
