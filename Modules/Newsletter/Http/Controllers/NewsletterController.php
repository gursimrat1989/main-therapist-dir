<?php

namespace Modules\Newsletter\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use DataTables;

use Modules\Newsletter\Entities\SubscribersList;

class NewsletterController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('newsletter::lists/index');
    }

    /**
     * Article list
     * @return Response
     */
    public function list( Request $request )
    {
        if ($request->ajax())
        {

            $data = SubscribersList::latest()->get();
            return Datatables::of($data)

                    ->addIndexColumn()
                    ->addColumn('action', function($row)
                    {
                            $editRoute = route('subs_list_edit', ['id' => $row->id]);
                            $deleteAction = route('subs_list_delete', ['id' => $row->id]);
                            $viewAction = route('subs_list_view', ['id' => $row->id]);
                            $deleteLink = '<a href="javascript:void(0)" class="edit red"><i class="fas fa-trash delete-list"></i></a>';

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
        return view('newsletter::lists/index');
    }

    /**
     * Article view
     * @return Response
     */
    public function view( $id )
    {
        $data = SubscribersList::find( $id );

        return $data;
    }

     /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        //
        return view('newsletter::lists/create');
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
        $listData['list_name'] = $data['list_name'];
        $blogObject = SubscribersList::create( $listData );

        //
        return redirect( route('subs-lists') )->with('success', 'List added successfully');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('newsletter::lists/show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
        $profileData = SubscribersList::find($id);

        //
        $dataToSend = [
            "data" => $profileData
        ];

        //
        return view('newsletter::lists/edit')
        ->with( $dataToSend );
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
        $article = SubscribersList::find( $id )->update( $dataToSave );
        

        //
        return redirect()->back()->with('success', 'List edited successfully');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
        $isDeleted = SubscribersList::where( 'id', $id )->delete();

        //
        return redirect()->back()->with('success', 'List deleted successfully');
    }
}
