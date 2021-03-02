<?php

namespace Modules\Laman\Http\Controllers\Epanel;

use Modules\Core\Http\Controllers\BaseController as Controller;
use Illuminate\Http\Request;

use Carbon\Carbon, Avatar, Image, Storage;

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
        $this->view = 'laman::epanel.laman';

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
        $data['user_id'] = optional(auth()->user())->id;

        $this->data->create($data);

        notify()->success($this->tCreate);
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
        $edit = $this->data->findOrFail($id);

        if($request->has('status')):
            $edit->update(['active' => $edit->active == 0 ? 1 : 0]);
            notify()->success(__('laman::general.changed'));
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
        $edit = $this->data->findOrFail($id);
        $edit->update($request->all());

        notify()->success($this->tUpdate);
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
                $each = $this->data->findOrFail($temp);
                $each->delete();
            endforeach;
            notify()->success($this->tDelete);
            return redirect()->back();
        endif;
        $satu = $this->data->findOrFail($id);
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
                $return .= '        <input type="checkbox" id="pilihan['.$data->id.']" name="pilihan[]" value="'.$data->id.'">';
                $return .= '        <label for="pilihan['.$data->id.']"></label>';
                $return .= '    </div>';
                $return .= '</span>';
                return $return;
            })
            ->editColumn('label', function($data) {
                $return  = $data->label;
                // $return .= '<div class="font-11 color-blue-grey-lighter">';
                // $return .= '    <i class="fa fa-link"></i> <a href="" target="_blank"><b>ss</b></a>';
                // $return .= '</div>';
                return $return;
            })
            ->editColumn('tanggal', function($data) {
                Carbon::setLocale('id');
                $return  = tgl_indo($data->updated_at) . '<br/>';
                $return .= '<small>' . $data->updated_at->diffForHumans() . '</small>';
                return $return;
            })
            ->editColumn('status', function($data) {
                $return  = '<div class="btn-toolbar">';
                if($data->active == 1):
                    $return .= '    <div class="btn-group btn-group-sm">';
                    $return .= '        <a href="'. route("$this->prefix.edit", $data->id) .'?status=true" class="btn btn-sm btn-success">';
                    $return .= '            <span class="fa fa-check"></span>';
                    $return .= '        </a>';
                    $return .= '    </div>';
                    $return .= '</div>';
                else:
                    $return .= '    <div class="btn-group btn-group-sm">';
                    $return .= '        <a href="'. route("$this->prefix.edit", $data->id) .'?status=true" class="btn btn-sm btn-danger">';
                    $return .= '            <span class="fa fa-times"></span>';
                    $return .= '        </a>';
                    $return .= '    </div>';
                    $return .= '</div>';
                endif;
                return $return;
            })
            ->editColumn('oleh', function($data) {
                return '<img src="' . Avatar::create(optional($data->user)->name)->toBase64() . '" data-toggle="tooltip" data-placement="top" data-original-title="Posted by ' . optional($data->user)->name . '">';
                return '';
            })
            ->editColumn('aksi', function($data) {
                $return  = '<div class="btn-toolbar">';
                $return .= '    <div class="btn-group btn-group-sm">';
                $return .= '        <a href="'. route("$this->prefix.edit", $data->id) .'" class="btn btn-primary">';
                $return .= '            <span class="fa fa-pencil"></span>';
                $return .= '        </a><a onclick="javascript:modalHapus(\''.$data->id.'\')" href="javascript:;" class="btn btn-danger">';
                $return .= '            <span class="fa fa-trash"></span>';
                $return .= '        </a>';
                $return .= '    </div>';
                $return .= '</div>';
                return $return;
            })
            ->rawColumns(['pilihan', 'label', 'tanggal', 'status', 'oleh', 'aksi'])->toJson();
    }
}
