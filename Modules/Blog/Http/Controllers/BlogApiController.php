<?php

namespace Modules\Blog\Http\Controllers;

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
use File;

use Modules\Blog\Entities\Blog;
use Modules\BlogCategories\Entities\BlogCategories;

class BlogApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkHeader');
    }

    /**
     *
     * @SWG\Post(
     *      path="/blog/article/add",
     *      tags={"Blog"},
     *      operationId="addBlogArticle",
     *      summary="Add blog article",
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
     *      @SWG\Parameter(
     *          name="title",
     *          in="formData",
     *          required=true, 
     *          type="string" 
     *      ),
     *       @SWG\Parameter(
     *          name="featured_image",
     *          in="formData",
     *          description="Featured image for the article.",
     *          type="file"
     *      ),
     *       @SWG\Parameter(
     *          name="is_featured",
     *          in="formData",
     *          description="Please use 1 for featuring article and 0 for not featuring.",
     *          type="integer"
     *      ),
     *      @SWG\Parameter(
     *          name="description",
     *          in="formData",
     *          required=true, 
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="category_id",
     *          in="formData",
     *          required=true, 
     *          type="string",
     *          description="E.g. 45,2,4,6,3"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="Success"
     *      ),
     *  )
     *
     */
    public function createBlogArticle(Request $request)
    {
        try {

            $data = array();
            $input = $request->all();

            $validator = Validator::make($input, [
                'title' => 'required',
                'description' => 'required',
                'category_id' => 'required',
            ]);

            // If validation error
            if($validator->fails()){
                return sendError('Validation Error.', $validator->errors());  
            }
            
            //If validation successfull
            $userData = [];
            $title = $request->title;
            $description    = $request->description;
            $user_id    = $request->user_id;
            $categoryId    = $request->category_id;
            $isFeatured = 0;
            
            //Is featured
            if( !empty( $request->is_featured ) )
            {
                $isFeatured  = $request->is_featured;
            }
            
            //
            $userData = ['title' => $title,
            'description' => $description,
            'user_id' => $user_id,
            'is_featured' => $isFeatured,
            'category_id' => 0];

            //Image upload
            $ranNumbers = uniqid();

            $file = $request->file('featured_image');
            
            if( $request->hasFile('featured_image') )
            {
                $fileName = $ranNumbers.'_featured_image.'.$file->getClientOriginalExtension();
                $userData['featured_image'] = $fileName;

                //File upload
                $destinationPath = 'featured_images';
                $file->move($destinationPath, $fileName);
            }

            //
            $blog = Blog::create( $userData );

            //
            if( $blog )
            {
                //Attach blog id with category in pivot table
                foreach (explode(',', $request->category_id) as $categoryId)
                {
                    $blog->blogCategories()->attach( $categoryId );
                }

                $blog = $blog; 
                return sendResponse($blog, 'Blog article added successfully.');
            }
        
        }
        catch (\Exception $e) {
            return sendError($e);
        }
    }

    /**
     *
     * @SWG\Post(
     *      path="/blog/category/add",
     *      tags={"Blog"},
     *      operationId="addBlogCategory",
     *      summary="Add blog category",
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
     *      @SWG\Parameter(
     *          name="title",
     *          in="formData",
     *          required=true, 
     *          type="string" 
     *      ),
     *      @SWG\Parameter(
     *          name="description",
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
    public function createBlogCategory(Request $request)
    {
        try {

            $data = array();
            $input = $request->all();

            $validator = Validator::make($input, [
                'title' => 'required',
                'description' => 'required',
            ]);

            // If validation error
            if($validator->fails()){
                return sendError('Validation Error.', $validator->errors());  
            }
            
            //If validation successfull
            $userData = [];
            $title = $request->title;
            $user_id = $request->user_id;
            $description    = $request->description;

            //
            $userData = ['name' => $title,
            'user_id' => $user_id,
            'description' => $description];

            //
            $blogCategory = BlogCategories::create( $userData );

            //
            if( $blogCategory )
            {
                $blogCategory = $blogCategory; 
                return sendResponse($blogCategory, 'Blog category added successfully.');
            }
        
        }
        catch (\Exception $e) {
            return sendError('there some issue at backend.');
        }
    }

    /**
     *
     * @SWG\Get(
     *      path="/blog/article/list",
     *      tags={"Blog"},
     *      operationId="blogArticleList",
     *      summary="Blog article list",
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
    public function blogArticleList(Request $request)
    {
        try {

            $data = array();

            $data = Blog::with('blogCategories')->get();
            
            return sendResponse($data, 'Blog article list fetched successfully.');
        
        }
        catch (\Exception $e) {
            return sendError('there some issue at backend.');
        }
    }

    /**
     *
     * @SWG\Get(
     *      path="/blog/article/list/featured",
     *      tags={"Blog"},
     *      operationId="blogFeaturedArticleList",
     *      summary="Featured article list",
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
    public function blogFeaturedArticleList(Request $request)
    {
        try {

            $data = array();

            $data = Blog::with('blogCategories')->where('is_featured', 1)->get();
            
            return sendResponse($data, 'Featured blog article list fetched successfully.');
        
        }
        catch (\Exception $e) {
            return sendError('there some issue at backend.');
        }
    }

    /**
     *
     * @SWG\Get(
     *      path="/blog/category/list",
     *      tags={"Blog"},
     *      operationId="blogCategoryList",
     *      summary="Blog category list",
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
    public function blogCategoryList(Request $request)
    {
        try {

            $data = array();

            $data = BlogCategories::all();
            
            return sendResponse($data, 'Blog category list fetched successfully.');
        
        }
        catch (\Exception $e) {
            return sendError('there some issue at backend.');
        }
    }

    /**
     *
     * @SWG\Get(
     *      path="/blog/search",
     *      tags={"Blog"},
     *      operationId="searchBlog",
     *      summary="Blog Search",
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
    public function blogSearch( Request $request )
    {
        try {

            $keyword = $request->keyword;
            $searchCount = 0;

            //
            $data = Blog::with('blogCategories')
            ->where('title', 'LIKE', "%$keyword%")
            ->orWhere('description', 'LIKE', "%$keyword%")
            ->get();

            $searchCount = count( @$data );

            //
            if( $data )
            {
                $data = $data; 
                return sendResponse($data, $searchCount.' Blog search results.');
            }
        
        }
        catch (\Exception $e) {
            return sendError('there some issue at backend.');
        }
    }

    /**
     *
     * @SWG\Post(
     *      path="/blog/delete",
     *      tags={"Blog"},
     *      operationId="blogArticleDelete",
     *      summary="Delete blog article",
     *      @SWG\Parameter(
     *          name="X-custom-header",
     *          in="header",
     *          description="header",
     *          required=true,
     *          type="string",
     *          default="7890abcdefghijkl"
     *      ),
     *      @SWG\Parameter(
     *          name="article_id",
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
    public function deleteBlogArticle(Request $request)
    {
        try {

            $data = array();

            $id = $request->article_id;

            $blogObject = Blog::find($id);

            $file_path = public_path().'/featured_images/'.$blogObject->featured_image;

            File::delete($file_path);

            $isDeleted = Blog::where( 'id', $id )->delete();

            //Dettach all the categories
            $blogObject->blogCategories()->detach();
            
            return sendResponse($data, 'Blog article deleted successfully.');
        
        }
        catch (\Exception $e) {
            return sendError($e);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('blog::create');
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
        return view('blog::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('blog::edit');
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
