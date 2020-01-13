<?php

namespace Modules\Blog\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use DataTables;

use Modules\Blog\Entities\Blog;
use Modules\BlogCategories\Entities\BlogCategories;

use Auth;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        //$categories = Blog::find(1)->blogCategories();
        //$blogs = BlogCategories::find(1)->blogs;
        //dd( $blogs );
        return view('blog::index');
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
            $getData = New Blog;
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
                    ->editColumn('category', function($row)
                    {
                        $blogObject = Blog::find($row->id);
                        
                        $categories = [];

                        foreach($blogObject->blogCategories as $get):
                           $categories[] = $get->name;
                        endforeach; 

                        return empty( $categories ) ? 'N/A' : implode(', ', $categories);
                    })
                    ->addColumn('action', function($row)
                    {
                            $editRoute = route('article_edit', ['id' => $row->id]);
                            $deleteAction = route('article_delete', ['id' => $row->id]);
                            $viewAction = route('article_view', ['id' => $row->id]);
                            $deleteLink = '<a href="javascript:void(0)" class="edit red"><i class="fas fa-trash delete-article"></i></a>';

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
        return view('blog::index');
    }

    /**
     * Article view
     * @return Response
     */
    public function view( $id )
    {
        $articles = Blog::find( $id );
        $featuredImageUrl = "";
        $imagePath = "";

        if( !empty( $articles->featured_image ) )
        {
            $featuredImageUrl = url('/').'/featured_images/'.$articles->featured_image;
            $imagePath = '<img width="200" src="' . $featuredImageUrl . '">';
        }
        
        $articles->featured_image = $imagePath;
        return $articles;
    }

     /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        //
        $blogCategories = BlogCategories::pluck('name', 'id');

        return view('blog::create')->with('blogCategories', $blogCategories);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $data['profile_pic'] = '';

        $categorIdsArray = $data['category_ids'];

        $userId = Auth::user()->id;

        $data['user_id'] = $userId;

        $ranNumbers = uniqid();

        $categoryId = 0;

        $file = $request->file('featured_image');

        //Featured Image
        if( $request->hasFile('featured_image') )
        {
            $fileName = $ranNumbers.'_featured_image.'.$file->getClientOriginalExtension();
            $data['featured_image'] = $fileName;

            //File upload
            $destinationPath = 'featured_images';
            $file->move($destinationPath, $fileName);
        }

        //
        $data['category_id'] = 0;
        $blogObject = Blog::create( $data );

        foreach ($categorIdsArray as $categoryId)
        {
            $blogObject->blogCategories()->attach( $categoryId );
        }

        //
        return redirect( route('articles') )->with('success', 'Article added successfully');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('blog::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
        $profileData = Blog::find($id);
        $savedCategoryIds = [];

        //
        $categorIdsArray = $profileData->blogCategories;

        foreach( $categorIdsArray as $get )
        {
            array_push( $savedCategoryIds, $get->id );
        }

        //
        $dataToSend = [
            "data" => $profileData
        ];

        //
        $blogCategories = BlogCategories::pluck('name', 'id');

        //
        return view('blog::edit')
        ->with( $dataToSend )
        ->with('blogCategories', $blogCategories)
        ->with('savedCategoriesIds', $savedCategoryIds);
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

        $is_featured = 0;

        //Get article data
        $articleData = Blog::find( $id );

        //Unset data
        unset( $dataToSave['_token'] );
        $dataToSave['user_id'] = Auth::user()->id;

        //Is featured
        if( $request->is_featured )
        {
            $is_featured = 1;
        }

        $dataToSave['is_featured'] = $is_featured;

        $categorIdsArray = $dataToSave['category_ids'];

        //Featured image upload
        $ranNumbers = uniqid();

        $file = $request->file('featured_image');

        if( $request->hasFile('featured_image') )
        {
            //Update file name
            if( $articleData->featured_image == null )
            {
                $fileName = $ranNumbers.'_featured_image'.$file->getClientOriginalExtension();
            }
            else
            {
                $fileName = $articleData->featured_image;
            }

            $dataToSave['featured_image'] = $fileName;

            //File upload
            $destinationPath = 'featured_images';
            $file->move($destinationPath, $fileName);
        }
        else
        {
            unset( $dataToSave['featured_image'] );
        }

        //
        $dataToSave['category_id'] = 0;

        //Save data
        $article = Blog::find( $id )->update( $dataToSave );

        //
        $blogObject = Blog::find( $id );

        //Dettach all the categories
        $blogObject->blogCategories()->detach();

        foreach ($categorIdsArray as $categoryId)
        {
            $blogObject->blogCategories()->attach( $categoryId );
        }

        

        //
        return redirect()->back()->with('success', 'Article edited successfully');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
        $blogObject = Blog::find($id);
        
        //
        $file_path = public_path().'/featured_images/'.$blogObject->featured_image;

        unlink($file_path);

        $isDeleted = Blog::where( 'id', $id )->delete();

        //Dettach all the categories
        $blogObject->blogCategories()->detach();

        //
        return redirect()->back()->with('success', 'Article deleted successfully');
    }
}
