<?php

namespace Modules\Techniques\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use DataTables;

use Modules\Techniques\Entities\Technique;

class TechniquesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('techniques::index');
    }

    /**
     * Technique list
     * @return Response
     */
    public function list( Request $request )
    {
        if ($request->ajax())
        {

            $data = Technique::latest()->get();
            return Datatables::of($data)

                    ->addIndexColumn()
                    ->addColumn('action', function($row)
                    {
                            $editRoute = route('technique_edit', ['id' => $row->id]);
                            $deleteAction = route('technique_delete', ['id' => $row->id]);
                            $viewAction = route('technique_view', ['id' => $row->id]);
                            $deleteLink = '<a href="javascript:void(0)" class="edit red"><i class="fas fa-trash delete-technique"></i></a>';

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
        return view('techniques::index');
    }

    /**
     * Technique view
     * @return Response
     */
    public function view( $id )
    {
        $techniques = Technique::find( $id );

        return $techniques;
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('techniques::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        //
        Technique::create( $data );

        //
        return redirect( route('techniques') )->with('success', 'Technique added successfully');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('techniques::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
        $technique = Technique::find($id);

        //
        $dataToSend = [
            "data" => $technique
        ];

        //
        return view('techniques::edit')->with( $dataToSend );
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
        $technique = Technique::find( $id )->update( $dataToSave );

        //
        return redirect()->back()->with('success', 'Technique edited successfully');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
        $isDeleted = Technique::where( 'id', $id )->delete();

        //
        return redirect()->back()->with('success', 'Technique deleted successfully');
    }
}
