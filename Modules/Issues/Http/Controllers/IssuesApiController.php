<?php

namespace Modules\Issues\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Validator;
use Auth;
use DB;
use Carbon\Carbon;
use Hash;
use Exception;

use Modules\Issues\Entities\Issue;
use Modules\Issues\Entities\IssueDescription;

class IssuesApiController extends Controller
{
    /**
     *
     * @SWG\Get(
     *      path="/issues/list",
     *      tags={"Issues"},
     *      operationId="listIssues",
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
    public function issuesList( Request $request )
    {
        try {
            //
            $issueData = Issue::where('user_id', $request->user_id)
            ->get();

            //
            if( $issueData )
            {
                $issueData = $issueData; 
                return sendResponse($issueData, 'Issues list fetched successfully.');
            }
        
        }
        catch (\Exception $e) {
            return sendError('there some issue at backend.');
        }
    }

    /**
     *
     * @SWG\Post(
     *      path="/issue/add",
     *      tags={"Issues"},
     *      operationId="addIssue",
     *      summary="Add issue",
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
    public function addIssue( Request $request )
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
            $issueData = Issue::create( $userData );

            //
            if( $issueData )
            {
                $issueData = $issueData; 
                return sendResponse($issueData, 'Issue created successfully.');
            }
        
        }
        catch (\Exception $e) {
            return sendError('there some issue at backend.');
        }
    }

    /**
     *
     * @SWG\Post(
     *      path="/issues/add/description",
     *      tags={"Issues"},
     *      operationId="issuesDescriptionAdd",
     *      summary="Add issue description by users",
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
     *          name="issue_id",
     *          in="formData",
     *          required=true, 
     *          type="integer" 
     *      ),
     *      @SWG\Parameter(
     *          name="issue_description",
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
    public function issuesDescriptionAdd( Request $request )
    {
        try {

            $savedData = '';

            $input = $request->all();

            //
            $savedData = IssueDescription::create( $input );

            //
            if( $savedData )
            {
                return sendResponse($savedData, 'Issue description added successfully');
            }
        
        }
        catch (\Exception $e) {
            return sendError($e);
        }
    }

    /**
     *
     * @SWG\Get(
     *      path="/issues/list/description",
     *      tags={"Issues"},
     *      operationId="issuesDescriptionList",
     *      summary="List issue description by users",
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
     *      @SWG\Parameter(
     *          name="issue_id",
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
    public function issuesDescriptionList( Request $request )
    {
        try {

            $savedData = '';

            $input = $request->all();
            
            //
            $data = IssueDescription::where( 'user_id', $input['user_id'] )
            ->where('issue_id', $input['issue_id'])
            ->get();

            //
            if( !empty( $data ) )
            {
                return sendResponse($data, 'Issue description fetched successfully');
            }
        
        }
        catch (\Exception $e) {
            return sendError($e);
        }
    }

    /**
     *
     * @SWG\Get(
     *      path="/issues/get/name",
     *      tags={"Issues"},
     *      operationId="issuesGetName",
     *      summary="Get issue name by id",
     *      @SWG\Parameter(
     *          name="X-custom-header",
     *          in="header",
     *          description="header",
     *          required=true,
     *          type="string",
     *          default="7890abcdefghijkl"
     *      ),
     *      @SWG\Parameter(
     *          name="issue_id",
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
    public function issueGetName( Request $request )
    {
        try {

            $data = [];
            
            //
            $issueName = Issue::select('name')
            ->where('id', $request->issue_id)
            ->first()->name;

            //
            $data['issue_name'] = $issueName;

            //
            return sendResponse($data, 'Issue name fetched successfully');
        
        }
        catch (\Exception $e) {
            return sendError($e);
        }
    }

    /**
     *
     * @SWG\Post(
     *      path="/issues/description/update",
     *      tags={"Issues"},
     *      operationId="issuesDescriptionUpdate",
     *      summary="Update issue description or explanation",
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
    public function issuesDescriptionUpdate( Request $request )
    {
        try {

            $savedData = '';
            
            $input = $request->all();

            //
            $savedData = IssueDescription::find( $request->description_id );
            $savedData->issue_description = $request->description;
            $savedData->save();

            //
            if( $savedData )
            {
                return sendResponse($savedData, 'Issue description updated successfully');
            }
        
        }
        catch (\Exception $e) {
            return sendError($e);
        }
    }

    /**
     *
     * @SWG\Post(
     *      path="/issues/description/delete",
     *      tags={"Issues"},
     *      operationId="issuesDescriptionDelete",
     *      summary="Delete issue description or explanation",
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
    public function issuesDescriptionDelete( Request $request )
    {
        try {

            $savedData = '';
            
            $input = $request->all();

            //
            $savedData = IssueDescription::find( $request->description_id );
            $savedData->delete();

            //
            if( $savedData )
            {
                return sendResponse($savedData, 'Issue description deleted successfully');
            }
        
        }
        catch (\Exception $e) {
            return sendError($e);
        }
    }

    /**
     *
     * @SWG\Get(
     *      path="/issues/search",
     *      tags={"Issues"},
     *      operationId="searchIssues",
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
    public function issuesSearch( Request $request )
    {
        try {

            $keyword = $request->keyword;
            $userId = $request->user_id;
            $searchCount = 0;

            //
            $issuesData = Issue::where('user_id', $userId)
            ->where(function($query) use ($keyword){
                $query->where('name', 'LIKE', "%$keyword%");
                $query->orWhere('description', 'LIKE', "%$keyword%");
            })
            ->get();

            $searchCount = count( @$issuesData );

            //
            if( $issuesData )
            {
                $issuesData = $issuesData; 
                return sendResponse($issuesData, $searchCount.' Issues search results.');
            }
        
        }
        catch (\Exception $e) {
            return sendError('there some issue at backend.');
        }
    }

    /**
     *
     * @SWG\Get(
     *      path="/issue/delete",
     *      tags={"Issues"},
     *      operationId="deleteIssue",
     *      summary="Delete issue",
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
     *          type="string" 
     *      ),
     *      @SWG\Parameter(
     *          name="issue_id",
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
    public function deleteIssue( Request $request )
    {
        try {

            $data = array();
            $id = $request->issue_id;
            $userId = $request->user_id;

            $message = 'Operation failed';

            $isDeleted = Issue::where( 'id', $id )
            ->where('user_id', $userId)
            ->delete();
            
            if( $isDeleted )
            {
                $message = 'Issue delted succesfully';
            }

            return sendResponse($data, $message);
        
        }
        catch (\Exception $e) {
            return sendError('there some issue at backend.');
        }
    }
}
