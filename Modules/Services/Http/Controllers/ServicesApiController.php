<?php

namespace Modules\Services\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Validator;
use Auth;
use DB;
use Carbon\Carbon;
use Hash;
use Exception;

use Modules\Services\Entities\Service;

class ServicesApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkHeader');
    }

    /**
     *
     * @SWG\Get(
     *      path="/services/list",
     *      tags={"Services"},
     *      operationId="listServices",
     *      summary="Services List",
     *      @SWG\Parameter(
     *          name="X-custom-header",
     *          in="header",
     *          description="header",
     *          required=true,
     *          type="string",
     *          default="7890abcdefghijkl"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="Success"
     *      ),
     *  )
     *
     */
    public function servicesList( Request $request )
    {
        try {
            //
            $issueData = Service::all();

            //
            if( $issueData )
            {
                $issueData = $issueData; 
                return sendResponse($issueData, 'Services list fetched successfully.');
            }
        
        }
        catch (\Exception $e) {
            return sendError('there some issue at backend.');
        }
    }
    
    /**
     *
     * @SWG\Post(
     *      path="/service/add",
     *      tags={"Services"},
     *      operationId="addService",
     *      summary="Add Service",
     *      @SWG\Parameter(
     *          name="X-custom-header",
     *          in="header",
     *          description="header",
     *          required=true,
     *          type="string",
     *          default="7890abcdefghijkl"
     *      ),
     *      @SWG\Parameter(
     *          name="user_id",
     *          in="formData",
     *          required=true, 
     *          type="integer" 
     *      ),
     *      @SWG\Parameter(
     *          name="name",
     *          in="formData",
     *          required=true, 
     *          type="string" 
     *      ),
     *      @SWG\Parameter(
     *          name="description",
     *          in="formData",
     *          required=true, 
     *          type="string" 
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="Success"
     *      ),
     *  )
     *
     */
    public function addService(Request $request)
    {
        try {

            $data = array();
            $input = $request->all();

            $validator = Validator::make($input, [
                'user_id' => 'required',
                'name' => 'required',
                'description' => 'required',
            ]);

            // If validation error
            if($validator->fails()){
                return sendError('Validation Error.', $validator->errors());  
            }
            
            //If validation successfull
            $userData = [];
            $userId = $request->user_id;
            $name = $request->name;
            $description    = $request->description;

            //
            $userData = ['name' => $name,
            'user_id' => $userId,
            'description' => $description];

            //
            $technique = Service::create( $userData );

            //
            if( $technique )
            {
                $technique = $technique; 
                return sendResponse($technique, 'Service added successfully.');
            }
        
        }
        catch (\Exception $e) {
            return sendError('there some issue at backend.');
        }
    }

    /**
     *
     * @SWG\Get(
     *      path="/services/search",
     *      tags={"Services"},
     *      operationId="searchSearch",
     *      summary="Services Search",
     *      @SWG\Parameter(
     *          name="X-custom-header",
     *          in="header",
     *          description="header",
     *          required=true,
     *          type="string",
     *          default="7890abcdefghijkl"
     *      ),
     *      @SWG\Parameter(
     *          name="keyword",
     *          in="query",
     *          required=true, 
     *          type="string" 
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="Success"
     *      ),
     *  )
     *
     */
    public function servicesSearch( Request $request )
    {
        try {

            $keyword = $request->keyword;
            $searchCount = 0;

            //
            $data = Service::where('name', 'LIKE', "%$keyword%")
            ->orWhere('description', 'LIKE', "%$keyword%")
            ->get();

            $searchCount = count( @$data );

            //
            if( $data )
            {
                $data = $data; 
                return sendResponse($data, $searchCount.' Service search results.');
            }
        
        }
        catch (\Exception $e) {
            return sendError('there some issue at backend.');
        }
    }

    /**
     *
     * @SWG\Get(
     *      path="/service/delete",
     *      tags={"Services"},
     *      operationId="deleteService",
     *      summary="Delete service",
     *      @SWG\Parameter(
     *          name="X-custom-header",
     *          in="header",
     *          description="header",
     *          required=true,
     *          type="string",
     *          default="7890abcdefghijkl"
     *      ),
     *      @SWG\Parameter(
     *          name="service_id",
     *          in="query",
     *          required=true, 
     *          type="integer" 
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="Success"
     *      ),
     *  )
     *
     */
    public function deleteService(Request $request)
    {
        try {

            $data = array();
            $id = $request->service_id;

            $isDeleted = Service::where( 'id', $id )->delete();
            
            return sendResponse($data, 'Service deleted successfully.');
        
        }
        catch (\Exception $e) {
            return sendError('there some issue at backend.');
        }
    }
}
