<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @SWG\Swagger(
 *   basePath="/therapist-dir/public/api",
 *   @SWG\Info(
 *     title="Therapist API's",
 *     version="1.0",
 *     description="API's for the Therapist project",
 *     @SWG\Contact(
 *         email="gursimrat1989@gmail.com"
 *     )
 *   )
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
