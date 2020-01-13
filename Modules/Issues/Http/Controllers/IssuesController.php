<?php

namespace Modules\Issues\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use DataTables;
use Auth;

use Modules\Issues\Entities\Issue;

class IssuesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('issues::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('issues::create');
    }

    /**
     * Article list
     * @return Response
     */
    public function list( Request $request )
    {
        if ($request->ajax())
        {
            //
            $user = auth()->user();
            $currentUserId = $user->id;

            //
            $getData = New Issue;
            $data = $getData->latest()->get();

            if(!$user->isAdmin())
            {
                $data = $getData
                ->where('user_id', $currentUserId)
                ->latest()
                ->get();
            }

            return Datatables::of($data)

                    ->addIndexColumn()
                    ->addColumn('action', function($row)
                    {
                            $editRoute = route('issue_edit', ['id' => $row->id]);
                            $deleteAction = route('issue_delete', ['id' => $row->id]);
                            $viewAction = route('issue_view', ['id' => $row->id]);
                            $deleteLink = '<a href="javascript:void(0)" class="edit red"><i class="fas fa-trash delete-issue"></i></a>';

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
        return view('issues::index');
    }

    /**
     * Issue view
     * @return Response
     */
    public function view( $id )
    {
        $issues = Issue::find( $id );

        return $issues;
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
        Issue::create( $data );

        //
        return redirect( route('issues') )->with('success', 'Issue added successfully');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('issues::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
        $issuesData = Issue::find($id);

        //
        $dataToSend = [
            "data" => $issuesData
        ];


        //
        return view('issues::edit')
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
        $article = Issue::find( $id )->update( $dataToSave );

        //
        return redirect()->back()->with('success', 'Issue updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
        $isDeleted = Issue::where( 'id', $id )->delete();

        //
        return redirect()->back()->with('success', 'Issue deleted successfully');
    }
}