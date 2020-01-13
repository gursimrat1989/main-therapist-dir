<?php

namespace Modules\Newsletter\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Validator;
use Auth;
use DB;
use Carbon\Carbon;
use Hash;
use Exception;

use Modules\Newsletter\Entities\SubscribersList;
use Modules\Newsletter\Entities\Subscribers;

class NewsletterApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkHeader');
    }

    /**
     *
     * @SWG\Get(
     *      path="/newsletter/list/show",
     *      tags={"Newsletter"},
     *      operationId="showNewsletterList",
     *      summary="Show newsletter list",
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
    public function showNewsletterList(Request $request)
    {
        try {

            $data = [];

            //
            $data = SubscribersList::all();

            //
            return sendResponse($data, 'Newsletter list fetched successfully.');
            
        
        }
        catch (\Exception $e) {
            return sendError($e);
        }
    }

    /**
     *
     * @SWG\Post(
     *      path="/newsletter/subscriber/add",
     *      tags={"Newsletter"},
     *      operationId="addSubscriber",
     *      summary="Add subscriber",
     *      @SWG\Parameter(
     *          name="X-custom-header",
     *          in="header",
     *          description="header",
     *          required=true,
     *          type="string",
     *          default="7890abcdefghijkl"
     *      ),
     *      @SWG\Parameter(
     *          name="subscriber_email",
     *          in="query",
     *          required=true, 
     *          type="string" 
     *      ),
     *      @SWG\Parameter(
     *          name="list_id",
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
    public function addSubscriber(Request $request)
    {
        try {

            $data = array();
            $input = $request->all();

            $validator = Validator::make($input, [
                'subscriber_email' => 'required',
                'list_id' => 'required',
            ]);

            // If validation error
            if($validator->fails()){
                return sendError('Validation Error.', $validator->errors());  
            }
            
            //If validation successfull
            $userData = [];
            $email = $request->subscriber_email;
            $listId    = $request->list_id;

            //
            $dataToSave = ['email' => $email];

            //
            $subscriber = Subscribers::create( $dataToSave );

            //
            if( $subscriber )
            {
                $subscriber->subscribersList()->attach( $listId );

                $data = $subscriber; 
                return sendResponse($data, 'Subscriber added successfully.');
            }
            
        
        }
        catch (\Exception $e) {
            return sendError($e);
        }
    }

}
