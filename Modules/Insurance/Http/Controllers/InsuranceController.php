<?php

namespace Modules\Insurance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use DataTables;
use Auth;

use Modules\Insurance\Entities\Insurance;

class InsuranceController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('insurance::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('insurance::create');
    }

    /**
     * Article list
     * @return Response
     */
    public function list( Request $request )
    {
        if ($request->ajax())
        {

            $data = Insurance::latest()->get();
            return Datatables::of($data)

                    ->addIndexColumn()
                    ->addColumn('action', function($row)
                    {
                            $editRoute = route('insurance_edit', ['id' => $row->id]);
                            $deleteAction = route('insurance_delete', ['id' => $row->id]);
                            $viewAction = route('insurance_view', ['id' => $row->id]);
                            $deleteLink = '<a href="javascript:void(0)" class="edit red"><i class="fas fa-trash delete-insurance"></i></a>';

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
        return view('insurance::index');
    }

    /**
     * Issue view
     * @return Response
     */
    public function view( $id )
    {
        $insurance = Insurance::find( $id );

        return $insurance;
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
        Insurance::create( $data );

        //
        return redirect( route('insurance') )->with('success', 'Insurance added successfully');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('insurance::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
        $issuesData = Insurance::find($id);

        //
        $dataToSend = [
            "data" => $issuesData
        ];


        //
        return view('insurance::edit')
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
        $insurance = Insurance::find( $id )->update( $dataToSave );

        //
        return redirect()->back()->with('success', 'Insurance updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
        $isDeleted = Insurance::where( 'id', $id )->delete();

        //
        return redirect()->back()->with('success', 'Insurance deleted successfully');
    }
}
