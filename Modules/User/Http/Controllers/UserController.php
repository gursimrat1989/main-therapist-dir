<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use DataTables;

//use Modules\Claims\Entities\Customer

use App\User;
use App\Profile;

use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {

        //Auth::user()->roles()->attach(1);
        //Auth::user()->roles()->detach(1);
        return view('user::index');
    }

    /**
     * Get user list
     * @return Response
     */
    public function list( Request $request )
    {

        if ($request->ajax())
        {

            $data = User::latest()->get();
            return Datatables::of($data)

                    ->addIndexColumn()
                    ->addColumn('action', function($row)
                    {
                            $editRoute = route('user_edit', ['id' => $row->id]);
                            $deleteAction = route('user_delete', ['id' => $row->id]);
                            $viewAction = route('user_view', ['id' => $row->id]);
                            $deleteLink = '';

                            if( Auth::user()->id != $row->id )
                            {
                                $deleteLink = '<a href="javascript:void(0)" class="edit red"><i class="fas fa-trash delete-user"></i></a>';
                            }

                            $btn = '<div><a href="javascript:void(0)" class="edit green"><i data="'.$viewAction.'" class="fas fa-eye show-view"></i></a>
                            &nbsp<a href="'.$editRoute.'" class="edit"><i class="fas fa-edit"></i></a>
                            &nbsp'.$deleteLink.'

                            <form action="'.$deleteAction.'" method="post">
                            '.csrf_field().'
                            </form></div>
                            ';
                            return $btn;
                    })

                    ->rawColumns(['action'])
                    ->make(true);

        }
        return view('user::index');
    }

    /**
     * User view
     * @return Response
     */
    public function view( $id )
    {
        $usersData = User::with('profile')->find( $id );

        return $usersData;
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('user::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $data['password'] = Hash::make( $data['password'] );

        //Validation
        $validatedData = $request->validate([
            'email' => 'required|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

        //
        User::create( $data );

        //
        return redirect( route('admin-user') )->with('success', 'User created successfully');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //return view('user::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
        $profileData = Profile::where('user_id', $id)->first();

        //
        $dataToSend = [
            "data" => $profileData,
            "userId" => $id
        ];

        //
        return view('user::edit')->with( $dataToSend );
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
        $dataToSave = $request->all();
        $file = $request->file('profile_pic');

        if( $request->hasFile('profile_pic') )
        {
            $fileName = $id.'_profile_pic.'.$file->getClientOriginalExtension();
            $dataToSave['profile_pic'] = $fileName;

            //File upload
            $destinationPath = 'profile_photos';
            $file->move($destinationPath, $fileName);
        }
        else
        {
            unset( $dataToSave['profile_pic'] );
        }

        //Unset data
        unset( $dataToSave['_token'] );

        $fullAddress = $dataToSave['address_1'].','.$dataToSave['address_2'];
        
        $this->getLatlang( $fullAddress );

        //Save data
        $profile = Profile::updateOrCreate(['user_id' => $id], $dataToSave);

        //
        return redirect()->back()->with('success', 'User edited successfully');

    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
        $isDeleted = User::where( 'id', $id )->delete();

        if( $isDeleted )
        {
            Profile::where( 'user_id', $id )->delete();
        }

        //
        return redirect()->back()->with('success', 'User deleted successfully');
    }


    /* Get Lat Lang */
    public function getLatlang( $address )
    {
        $url = "http://www.mapquestapi.com/geocoding/v1/address?key=UhBZRDjdZDocqdrDeGmJYfS9A02GDzoB&location=".urlencode($address);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $data = curl_exec($ch);

        curl_close($ch);

        $latLng = json_decode($data)->results[0]->locations[0]->latLng; //$latLng->lat, $latLng->lng;

        curl_close($ch); // Close the connection

        return $latLng;
    }
}
