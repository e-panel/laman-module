<?php

namespace Modules\Laman\Http\Controllers\Front;

use Modules\Core\Http\Controllers\BaseController as Controller;
use Illuminate\Http\Request;

use Modules\Laman\Entities\Laman;

class IndexController extends Controller
{
    /**
     * Siapkan konstruktor controller
     * 
     * @param Laman $data
     */
    public function __construct(Laman $data) 
    {       
        $this->data = $data;

        $this->view = 'template::modules.laman';
        view()->share([
            'view' => $this->view
        ]);
    }

    /**
     * Tampilkan laman berdasarkan slug
     * 
     * @param String $slug
     * @return Response|View
     */
    public function index($slug) 
    {
        $data = $this->data->whereSlug($slug)->firstOrFail();

        return view("$this->view.index", compact('data'));
    }
}
