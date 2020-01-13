<?php

namespace Modules\Techniques\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Validator;
use Auth;
use DB;
use Carbon\Carbon;
use Hash;
use Exception;

use Modules\Techniques\Entities\Technique;
use Modules\Techniques\Entities\TechniqueDescription;

class TechniquesApiController extends Controller
{

    public function __construct()
    {
        $this->middleware('checkHeader');
    }

    /**
     *
     * @SWG\Get(
     *      path="/techniques/list",
     *      tags={"Techniques"},
     *      operationId="listTechniques",
     *      summary="Issues List",
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
    public function techniquesList( Request $request )
    {
        try {
            //
            $issueData = Technique::where('user_id', $request->user_id)
            ->get();

            //
            if( $issueData )
            {
                $issueData = $issueData; 
                return sendResponse($issueData, 'Techniques list fetched successfully.');
            }
        
        }
        catch (\Exception $e) {
            return sendError('there some issue at backend.');
        }
    }
    
    /**
     *
     * @SWG\Post(
     *      path="/technique/add",
     *      tags={"Techniques"},
     *      operationId="addTechnique",
     *      summary="Add technique",
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
     *          type="string" 
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
    public function addTechnique(Request $request)
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
            $technique = Technique::create( $userData );

            //
            if( $technique )
            {
                $technique = $technique; 
                return sendResponse($technique, 'Technique added successfully.');
            }
        
        }
        catch (\Exception $e) {
            return sendError('there some issue at backend.');
        }
    }

    /**
     *
     * @SWG\Post(
     *      path="/technique/add/description",
     *      tags={"Techniques"},
     *      operationId="techniqueDescriptionAdd",
     *      summary="Add technique description by users",
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
     *          name="technique_id",
     *          in="formData",
     *          required=true, 
     *          type="integer" 
     *      ),
     *      @SWG\Parameter(
     *          name="technique_description",
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
    public function techniqueDescriptionAdd( Request $request )
    {
        try {

            $savedData = '';

            $input = $request->all();

            //
            $savedData = TechniqueDescription::create( $input );

            //
            if( $savedData )
            {
                return sendResponse($savedData, 'Technique description added successfully');
            }
        
        }
        catch (\Exception $e) {
            return sendError($e);
        }
    }

    /**
     *
     * @SWG\Get(
     *      path="/technique/list/description",
     *      tags={"Techniques"},
     *      operationId="techniqueDescriptionList",
     *      summary="List technique description by users",
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
    public function techniqueDescriptionList( Request $request )
    {
        try {

            $savedData = '';

            $input = $request->all();

            //
            $data = TechniqueDescription::where( 'user_id', $input['user_id'] )->get();

            //
            if( $data )
            {
                return sendResponse($data, 'Technique description fetched successfully');
            }
        
        }
        catch (\Exception $e) {
            return sendError($e);
        }
    }

    /**
     *
     * @SWG\Get(
     *      path="/technique/get/name",
     *      tags={"Techniques"},
     *      operationId="techniqueGetName",
     *      summary="Get technique name by id",
     *      @SWG\Parameter(
     *          name="X-custom-header",
     *          in="header",
     *          description="header",
     *          required=true,
     *          type="string",
     *          default="7890abcdefghijkl"
     *      ),
     *      @SWG\Parameter(
     *          name="technique_id",
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
    public function techniqueGetName( Request $request )
    {
        try {

            $data = [];
            
            //
            $issueName = Technique::select('name')
            ->where('id', $request->technique_id)
            ->first()->name;

            //
            $data['technique_name'] = $issueName;

            //
            return sendResponse($data, 'Technique name fetched successfully');
        
        }
        catch (\Exception $e) {
            return sendError($e);
        }
    }

    /**
     *
     * @SWG\Post(
     *      path="/techniques/description/update",
     *      tags={"Techniques"},
     *      operationId="techniquesDescriptionUpdate",
     *      summary="Update technique description or explanation",
     *      @SWG\Parameter(
     *          name="X-custom-header",
     *          in="header",
     *          description="header",
     *          required=true,
     *          type="string",
     *          default="7890abcdefghijkl"
     *      ),
     *      @SWG\Parameter(
     *          name="description_id",
     *          in="formData",
     *          required=true, 
     *          type="integer" 
     *      ),
     *     @SWG\Parameter(
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
    public function techniquesDescriptionUpdate( Request $request )
    {
        try {

            $savedData = '';
            
            $input = $request->all();

            //
            $savedData = TechniqueDescription::find( $request->description_id );
            $savedData->technique_description = $request->description;
            $savedData->save();

            //
            if( $savedData )
            {
                return sendResponse($savedData, 'Technique description updated successfully');
            }
        
        }
        catch (\Exception $e) {
            return sendError($e);
        }
    }

    /**
     *
     * @SWG\Post(
     *      path="/techniques/description/delete",
     *      tags={"Techniques"},
     *      operationId="techniquesDescriptionDelete",
     *      summary="Delete technique description or explanation",
     *      @SWG\Parameter(
     *          name="X-custom-header",
     *          in="header",
     *          description="header",
     *          required=true,
     *          type="string",
     *          default="7890abcdefghijkl"
     *      ),
     *      @SWG\Parameter(
     *          name="description_id",
     *          in="formData",
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
    public function techniquesDescriptionDelete( Request $request )
    {
        try {

            $savedData = '';
            
            $input = $request->all();

            //
            $savedData = TechniqueDescription::find( $request->description_id );
            $savedData->delete();

            //
            if( $savedData )
            {
                return sendResponse($savedData, 'Technique description deleted successfully');
            }
        
        }
        catch (\Exception $e) {
            return sendError($e);
        }
    }

    /**
     *
     * @SWG\Get(
     *      path="/techniques/search",
     *      tags={"Techniques"},
     *      operationId="searchTechniques",
     *      summary="Techniques Search",
     *      @SWG\Parameter(
     *          name="X-custom-header",
     *          in="header",
     *          description="header",
     *          required=true,
     *          type="string",
     *          default="7890abcdefghijkl"
     *      ),
     *     @SWG\Parameter(
     *          name="user_id",
     *          in="query",
     *          required=true, 
     *          type="string" 
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
    public function techniquesSearch( Request $request )
    {
        try {

            $keyword = $request->keyword;
            $userId = $request->user_id;
            $searchCount = 0;

            //
            $data = Technique::where('user_id', $userId)
            ->where(function($query) use ($keyword){
                $query->where('name', 'LIKE', "%$keyword%");
                $query->orWhere('description', 'LIKE', "%$keyword%");
            })
            ->get();

            $searchCount = count( @$data );

            //
            if( $data )
            {
                $data = $data; 
                return sendResponse($data, $searchCount.' Techniques search results.');
            }
        
        }
        catch (\Exception $e) {
            return sendError('there some issue at backend.');
        }
    }

    /**
     *
     * @SWG\Get(
     *      path="/technique/delete",
     *      tags={"Techniques"},
     *      operationId="deleteTechnique",
     *      summary="Delete technique",
     *      @SWG\Parameter(
     *          name="X-custom-header",
     *          in="header",
     *          description="header",
     *          required=true,
     *          type="string",
     *          default="7890abcdefghijkl"
     *      ),
     * *      @SWG\Parameter(
     *          name="user_id",
     *          in="query",
     *          required=true, 
     *          type="string" 
     *      ),
     *      @SWG\Parameter(
     *          name="technique_id",
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
    public function deleteTechnique(Request $request)
    {
        try {

            $data = array();
            $id = $request->technique_id;
            $userId = $request->user_id;

            $message = 'Operation failed';

            $isDeleted = Technique::where( 'id', $id )
            ->where('user_id', $userId)
            ->delete();

            if( $isDeleted )
            {
                $message = 'Technique delted succesfully';
            }
            
            return sendResponse($data, $message);
        
        }
        catch (\Exception $e) {
            return sendError('there some issue at backend.');
        }
    }
}
