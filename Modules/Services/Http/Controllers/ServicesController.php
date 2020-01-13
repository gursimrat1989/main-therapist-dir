<?php

namespace Modules\Services\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use DataTables;
use Auth;

use Modules\Services\Entities\Service;

class ServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('services::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('services::create');
    }

    /**
     * Article list
     * @return Response
     */
    public function list( Request $request )
    {
        if ($request->ajax())
        {

            $data = Service::latest()->get();
            return Datatables::of($data)

                    ->addIndexColumn()
                    ->addColumn('action', function($row)
                    {
                            $editRoute = route('service_edit', ['id' => $row->id]);
                            $deleteAction = route('service_delete', ['id' => $row->id]);
                            $viewAction = route('service_view', ['id' => $row->id]);
                            $deleteLink = '<a href="javascript:void(0)" class="edit red"><i class="fas fa-trash delete-service"></i></a>';

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
        return view('services::index');
    }

    /**
     * Service view
     * @return Response
     */
    public function view( $id )
    {
        $services = Service::find( $id );

        return $services;
    }
     
    /**
     * Service store
     * @return Response
     */      
    public function store( Request $request )
    {
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;

        //
        Service::create( $data );

        //
        return redirect( route('admin-services') )->with('success', 'Service added successfully');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('services::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
        $serviceData = Service::find($id);

        //
        $dataToSend = [
            "data" => $serviceData
        ];


        //
        return view('services::edit')
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
        $dataToSave['user_id'] = Auth::user()->id; 

        //Save data
        $article = Service::find( $id )->update( $dataToSave );

        //
        return redirect()->back()->with('success', 'Service updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
        $isDeleted = Service::where( 'id', $id )->delete();

        //
        return redirect()->back()->with('success', 'Service deleted successfully');
    }
}
