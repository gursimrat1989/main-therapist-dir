<?php

namespace Modules\Insurance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Validator;
use Auth;
use DB;
use Carbon\Carbon;
use Hash;
use Exception;

use Modules\Insurance\Entities\Insurance;

class InsuranceApiController extends Controller
{
    /**
     *
     * @SWG\Get(
     *      path="/insurance/list",
     *      tags={"Insurance"},
     *      operationId="listInsurance",
     *      summary="Insurance List",
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
    public function insuranceList( Request $request )
    {
        try {
            //
            $insuranceData = Insurance::all();

            //
            if( $insuranceData )
            {
                $insuranceData = $insuranceData; 
                return sendResponse($insuranceData, 'Insurance list fetched successfully.');
            }
        
        }
        catch (\Exception $e) {
            return sendError('there some issue at backend.');
        }
    }

    /**
     *
     * @SWG\Post(
     *      path="/insurance/add",
     *      tags={"Insurance"},
     *      operationId="addInsurance",
     *      summary="Add insurance",
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
    public function addInsurance( Request $request )
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
            $name = $request->name;
            $description    = $request->description;
            $userId    = $request->user_id;

            //
            $userData = [
                'name' => $name,
                'description' => $description,
                'user_id' => $userId
                ];

            //
            $insuranceData = Insurance::create( $userData );

            //
            if( $insuranceData )
            {
                $insuranceData = $insuranceData; 
                return sendResponse($insuranceData, 'Insurance created successfully.');
            }
        
        }
        catch (\Exception $e) {
            return sendError('there some issue at backend.');
        }
    }

    /**
     *
     * @SWG\Get(
     *      path="/insurance/search",
     *      tags={"Insurance"},
     *      operationId="searchInsurance",
     *      summary="Insurance Search",
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
    public function insuranceSearch( Request $request )
    {
        try {

            $keyword = $request->keyword;
            $searchCount = 0;

            //
            $insuranceData = Insurance::where('name', 'LIKE', "%$keyword%")
            ->orWhere('description', 'LIKE', "%$keyword%")
            ->get();

            $searchCount = count( @$insuranceData );

            //
            if( $insuranceData )
            {
                $insuranceData = $insuranceData; 
                return sendResponse($insuranceData, $searchCount.' Insurance search results.');
            }
        
        }
        catch (\Exception $e) {
            return sendError('there some issue at backend.');
        }
    }

    /**
     *
     * @SWG\Get(
     *      path="/insurance/delete",
     *      tags={"Insurance"},
     *      operationId="deleteInsurance",
     *      summary="Delete insurance",
     *      @SWG\Parameter(
     *          name="X-custom-header",
     *          in="header",
     *          description="header",
     *          required=true,
     *          type="string",
     *          default="7890abcdefghijkl"
     *      ),
     *      @SWG\Parameter(
     *          name="insurance_id",
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
    public function deleteInsurance( Request $request )
    {
        try {

            $data = array();
            $id = $request->insurance_id;

            $isDeleted = Insurance::where( 'id', $id )->delete();
            
            return sendResponse($data, 'insurance deleted successfully.');
        
        }
        catch (\Exception $e) {
            return sendError('there some issue at backend.');
        }
    }
}
