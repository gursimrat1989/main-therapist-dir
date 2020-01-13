<?php

namespace Modules\BlogCategories\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use DataTables;
use Auth;

use Modules\Blog\Entities\Blog;
use Modules\BlogCategories\Entities\BlogCategories;

class BlogCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('blogcategories::index');
    }

    /**
     * Article category list
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
            $getData = New BlogCategories;
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
                            $editRoute = route('blog_category_edit', ['id' => $row->id]);
                            $deleteAction = route('blog_category_delete', ['id' => $row->id]);
                            $viewAction = route('blog_category_view', ['id' => $row->id]);
                            $deleteLink = '<a href="javascript:void(0)" class="edit red"><i class="fas fa-trash delete-article-category"></i></a>';

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
        return view('blogcategories::index');
    }

    /**
     * Article category view
     * @return Response
     */
    public function view( $id )
    {
        $articles = BlogCategories::find( $id );

        return $articles;
    }

     /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('blogcategories::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $data['user_id'] = Auth::user()->id;

        //
        BlogCategories::create( $data );

        //
        return redirect( route('blog-categories') )->with('success', 'Article category added successfully');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('blogcategories::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
        $categoryData = BlogCategories::find($id);

        //
        $dataToSend = [
            "data" => $categoryData
        ];

        //
        return view('blogcategories::edit')->with( $dataToSend );
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
        $article = BlogCategories::find( $id )->update( $dataToSave );

        //
        return redirect()->back()->with('success', 'Article category edited successfully');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
        $isDeleted = BlogCategories::where( 'id', $id )->delete();

        //
        return redirect()->back()->with('success', 'Article category deleted successfully');
    }
}
