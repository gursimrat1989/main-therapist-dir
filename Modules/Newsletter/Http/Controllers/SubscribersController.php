<?php

namespace Modules\Newsletter\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use DataTables;

use Modules\Newsletter\Entities\Subscribers;
use Modules\Newsletter\Entities\SubscribersList;


class SubscribersController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('newsletter::subscribers/index');
    }

    /**
     * Article list
     * @return Response
     */
    public function list( Request $request )
    {
        if ($request->ajax())
        {

            $data = Subscribers::latest()->get();
            return Datatables::of($data)

                    ->addIndexColumn()
                    ->editColumn('slist', function($row)
                    {
                        $blogObject = Subscribers::find($row->id);
                        
                        $listData =  json_decode( $blogObject->subscribersList );

                        return $listData[0]->list_name;
                    })
                    ->addColumn('action', function($row)
                    {
                            $editRoute = route('subs_edit', ['id' => $row->id]);
                            $deleteAction = route('subs_delete', ['id' => $row->id]);
                            $viewAction = route('subs_view', ['id' => $row->id]);
                            $deleteLink = '<a href="javascript:void(0)" class="edit red"><i class="fas fa-trash delete-subs"></i></a>';

                            $btn = '<div><a href="'.$editRoute.'" class="edit"><i class="fas fa-edit"></i></a>
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
        return view('newsletter::subscribers/index');
    }

    /**
     * Article view
     * @return Response
     */
    public function view( $id )
    {
        $data = Subscribers::find( $id );

        return $data;
    }

     /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        //
        $slists = SubscribersList::pluck('list_name', 'id');

        //
        return view('newsletter::subscribers/create')->with('slists', $slists);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $listData = [];

        //
        $listData['email'] = $data['email'];
        $listId = $data['slist'];
        $blogObject = Subscribers::create( $listData );

        //Attach blog id with category in pivot table
        $blogObject->subscribersList()->attach( $listId );

        //
        return redirect( route('subs') )->with('success', 'Subscriber added successfully');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('newsletter::subscribers/show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
        $profileData = Subscribers::find($id);

        //
        $slists = SubscribersList::pluck('list_name', 'id');

        //
        $dataToSend = [
            "data" => $profileData
        ];

        //
        return view('newsletter::subscribers/edit')
        ->with( $dataToSend )
        ->with( 'slists', $slists );
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
       
        //Unset data
        unset( $dataToSave['_token'] );

        //Save data
        $article = Subscribers::find( $id )->update( $dataToSave );
        

        //
        return redirect()->back()->with('success', 'Subscriber edited successfully');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
        $blogObject = Subscribers::find($id);
        $isDeleted = Subscribers::where( 'id', $id )->delete();

        //Dettach all the categories
        $blogObject->subscribersList()->detach();

        //
        return redirect()->back()->with('success', 'Subscriber deleted successfully');
    }
}
