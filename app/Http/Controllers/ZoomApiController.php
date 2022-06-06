<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Services\ZoomApiService;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ZoomApiController extends Controller
{
    /** @var ZoomApiService $zoomApiService */
    private $zoomApiService;

    public function __construct(ZoomApiService $zoomApiService)
    {
        $this->zoomApiService = $zoomApiService;
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('zoom.index', []);
    }


    public function createWebinarUrl(Request $request)
    {
        $data = $request->all();

        try {
            $response = $this->zoomApiService->createWebiner($data);
        } catch (\Exception $e) {
            return redirect('zoom.index');
        }

        dd(json_decode($response->body(), true));
    }
}
