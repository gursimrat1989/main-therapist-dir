<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Validator;
use Auth;
use DB;
use Carbon\Carbon;
use Hash;
use Exception;

use App\User;
use App\Profile;

class UserApiController extends Controller
{
   
    public function __construct()
    {
        $this->middleware('checkHeader');
    }

    /**
     *
     * @SWG\Post(
     *      path="/user/detail",
     *      tags={"User"},
     *      operationId="userDetail",
     *      summary="Get user details",
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
     *      @SWG\Response(
     *          response=200,
     *          description="Success"
     *      ),
     *  )
     *
     */
    public function userDetail(Request $request)
    {
        try {

            $data = array();
            $input = $request->all();
            $validator = Validator::make($input, [
                'user_id' => 'required',
            ]);

            // If validation error
            if($validator->fails()){
                return sendError('Validation Error.', $validator->errors());  
            }
            
            //If validation successfull
            $userData = User::with('profile')->get()->find( $request->user_id );

            if( $userData )
            {
                return sendResponse($userData, 'User details fetched successfully.');
            }
            else
            {
                return sendResponse($userData, 'User not found.');
            }
        
        }
        catch (\Exception $e) {
            return sendError('there some issue at backend.');
        }
    }

    /**
     *
     * @SWG\Post(
     *      path="/user/login",
     *      tags={"User"},
     *      operationId="loginUser",
     *      summary="Login a user",
     *      @SWG\Parameter(
     *          name="X-custom-header",
     *          in="header",
     *          description="header",
     *          required=true,
     *          type="string",
     *          default="7890abcdefghijkl"
     *      ),
     *      @SWG\Parameter(
     *          name="email",
     *          in="formData",
     *          required=true, 
     *          type="string" 
     *      ),
     *      @SWG\Parameter(
     *          name="password",
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
    public function userLogin(Request $request)
    {
        try {

            $data = array();
            $input = $request->all();
            $validator = Validator::make($input, [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            // If validation error
            if($validator->fails()){
                return sendError('Validation Error.', $validator->errors());  
            }
            
            //If login successfull
            if(Auth::attempt(['email' => $input['email'], 'password' => $input['password']]))
            { 
                $userData = Auth::user()->load('profile'); 
                //$data['token'] =  $data->createToken('MyApp')->accessToken; 
                return sendResponse($userData, 'User logged in successfully.');
            } 
            else
            { 
                return sendError('Incorrect credentials.');
            } 
        
        }
        catch (\Exception $e) {
            return sendError('there some issue at backend.');
        }
    }

    /**
     *
     * @SWG\Post(
     *      path="/user/register",
     *      tags={"User"},
     *      operationId="registerUser",
     *      summary="Register a user",
     *      @SWG\Parameter(
     *          name="X-custom-header",
     *          in="header",
     *          description="header",
     *          required=true,
     *          type="string",
     *          default="7890abcdefghijkl"
     *      ),
     *      @SWG\Parameter(
     *          name="name",
     *          in="formData",
     *          required=true, 
     *          type="string" 
     *      ),
     *      @SWG\Parameter(
     *          name="email",
     *          in="formData",
     *          required=true, 
     *          type="string" 
     *      ),
     *      @SWG\Parameter(
     *          name="password",
     *          in="formData",
     *          required=true, 
     *          type="string" 
     *      ),
     *      @SWG\Parameter(
     *          name="password_confirmation",
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
    public function userRegister(Request $request)
    {
        try {

            $data = array();
            $input = $request->all();
            $validator = Validator::make($input, [
                'email' => 'unique:users|required|email',
                'name' => 'required',
                'password' => 'required|confirmed|min:6',
            ]);

            // If validation error
            if($validator->fails()){
                return sendError('Validation Error.', $validator->errors());  
            }

            //If validation successfull
            $userData = [];
            $name = $request->name;
            $email    = $request->email;
            $password = $request->password;

            //
            $userData = ['name' => $name,
            'email' => $email,
            'password' => Hash::make($password)];

            //
            $user     = User::create( $userData );

            //
            if( $user )
            {
                $userData = $user; 
                return sendResponse($userData, 'User registered in successfully.');
            } 
            
            }
            catch (\Exception $e) {
                return sendError('there some issue at backend.');
            }
    }

    /**
     *
     * @SWG\Get(
     *      path="/users/search",
     *      tags={"User"},
     *      operationId="searchUsers",
     *      summary="Users Search",
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
    public function usersSearch( Request $request )
    {
        try {

            $keyword = $request->keyword;
            $searchCount = 0;

            //
            $data = User::where('name', 'LIKE', "%$keyword%")
            ->orWhere('email', 'LIKE', "%$keyword%")
            ->get();

            $searchCount = count( @$data );

            //
            if( $data )
            {
                $data = $data; 
                return sendResponse($data, $searchCount.' Users search results.');
            }
        
        }
        catch (\Exception $e) {
            return sendError('there some issue at backend.');
        }
    }

    /**
     *
     * @SWG\Get(
     *      path="/user/therapist/list",
     *      tags={"User"},
     *      operationId="therapistList",
     *      summary="User therapist list",
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
    public function therapistList( Request $request )
    {
        try {

            $allUsers = User::with('profile')->get();

            //
            if( $allUsers )
            {
                $data = $allUsers; 
                return sendResponse($data, ' Users(therapist) list fetched successfully.');
            }
        
        }
        catch (\Exception $e) {
            return sendError('there some issue at backend.');
        }
    }

    /**
     *
     * @SWG\Get(
     *      path="/user/get-by-name",
     *      tags={"User"},
     *      operationId="userByName",
     *      summary="Get user by name",
     *      @SWG\Parameter(
     *          name="X-custom-header",
     *          in="header",
     *          description="header",
     *          required=true,
     *          type="string",
     *          default="7890abcdefghijkl"
     *      ),
     *     @SWG\Parameter(
     *          name="user_name_slug",
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
    public function getUserByName( Request $request )
    {
        try {

            $nameSlug = $request->user_name_slug;

            $userName = str_replace('-', ' ', $nameSlug);
            
            $allUsers = User::with('profile')->where('name', $userName)->get();

            //
            if( $allUsers )
            {
                $data = $allUsers; 
                return sendResponse($data, 'User fetched successfully.');
            }
        
        }
        catch (\Exception $e) {
            return sendError('there some issue at backend.');
        }
    }

    /**
     * Create Slug.
     *
     * @var array
     */
    public static function slugify($text)
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        $text = preg_replace('~[^-\w]+~', '', $text);

        $text = trim($text, '-');

        $text = preg_replace('~-+~', '-', $text);

        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    /**
     *
     * @SWG\Post(
     *      path="/user/create/profile",
     *      tags={"User"},
     *      operationId="createProfile",
     *      summary="Create profile of a user",
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
     *          type="number" 
     *      ),
     *      @SWG\Parameter(
     *          name="first_name",
     *          in="formData",
     *          required=true, 
     *          type="string" 
     *      ),
     *      @SWG\Parameter(
     *          name="last_name",
     *          in="formData",
     *          required=true, 
     *          type="string" 
     *      ),
     *      @SWG\Parameter(
     *          name="contact_number",
     *          in="formData",
     *          required=true, 
     *          type="string" 
     *      ),
     *      @SWG\Parameter(
     *          name="address_1",
     *          in="formData",
     *          required=true, 
     *          type="string" 
     *      ),
     *      @SWG\Parameter(
     *          name="address_2",
     *          in="formData",
     *          required=true, 
     *          type="string" 
     *      ),
     *      @SWG\Parameter(
     *          name="bio",
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
    public function createProfile(Request $request)
    {
        try {

            $data = array();
            $input = $request->all();
            $validator = Validator::make($input, [
                'first_name' => 'required',
                'last_name' => 'required',
                'contact_number' => 'required',
                'address_1' => 'required',
                'address_2' => 'required',
                'user_id' => 'required',
                'bio' => 'required',
            ]);

            // If validation error
            if($validator->fails()){
                return sendError('Validation Error.', $validator->errors());  
            }
            
            //If validation successfull
            //$profile = Profile::updateOrCreate( $input );

            $profile = Profile::updateOrCreate(['user_id' => request()->user_id], $input);

            //
            if( $profile )
            {
                return sendResponse($profile, 'Profile created successfully.');
            }
            
            
            }
            catch (\Exception $e) {
                return sendError('there some issue at backend.');
            }
    }

    /**
     *
     * @SWG\Post(
     *      path="/user/update/profile/pic",
     *      tags={"User"},
     *      operationId="updateProfilePic",
     *      summary="Update profile pic of the user",
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
     *          type="number" 
     *      ),
     *      @SWG\Parameter(
     *          name="pic",
     *          in="formData",
     *          description="Profile picture for the user.",
     *          type="file"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="Success"
     *      ),
     *  )
     *
     */
    public function updateProfilePic(Request $request)
    {
        $id = $request->user_id;
        $file = $request->file('pic');

        if( $request->hasFile('pic') )
        {
            $fileName = $id.'_profile_pic.'.$file->getClientOriginalExtension();
            $dataToSave['profile_pic'] = $fileName;

            //File upload
            $destinationPath = 'profile_photos';
            $file->move($destinationPath, $fileName);
        }
        else
        {
            return sendError('Please select the profile picture');
        }

        //Update profile pic name in the database for the user
        $profile = Profile::updateOrCreate(['user_id' => $id], $dataToSave);


        //Response
        $response = [];
        return sendResponse($response, 'Profile picture updated successfully.');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('user::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('user::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
