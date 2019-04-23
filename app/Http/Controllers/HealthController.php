<?php

namespace App\Http\Controllers;

use App\Status;
use Illuminate\Http\JsonResponse;

/**
 * Application health status controller
 *
 * @package App\Http\Controllers
 */
class HealthController extends Controller
{

    /**
     * Shows components status info
     *
     * @return JsonResponse
     */
    public function showStatus()
    {
        $status = (new Status())->get();

        return response()->json($status);
    }

}
