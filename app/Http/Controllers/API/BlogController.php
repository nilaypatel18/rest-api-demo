<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Blog;
use App\Models\BlogImage;
use Validator;
use App\Http\Resources\Blog as BlogResource;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class BlogController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $blogs = Blog::where('is_active',1)->get();
        
        return $this->sendResponse($blogs, ' Blogs retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
 
        $validator = Validator::make($input, [
            'title' => 'required',
            'description' => 'required',
            'is_featured' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(),200);
        }

        $blog = new Blog();
        $blog->title = $input['title'];
        $blog->description = $input['description'];
        $blog->is_featured = $input['is_featured'];
        $blog->save();

        return $this->sendResponse($blog, 'Blog created successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $blog = Blog::with('blogImage')->where('id',$id)->first();
        
        if (is_null($blog)) {
            return $this->sendError('Blog not found.');
        }

        return $this->sendResponse($blog, 'Blog retrieved successfully.');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $input = $request->all();

        $blog=Blog::find($id);
          
        $validator = Validator::make($input, [
            'title' => 'required',
            'description' => 'required',
            'is_featured' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(),200);
        }
       
        $blog->title = $input['title'];
        $blog->description = isset($input['description'])?$input['description']:0;
        $blog->is_featured = isset($input['is_featured'])?$input['is_featured']:0;
        $blog->update();

        return $this->sendResponse($blog, 'Blog updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog){
        $blog->is_active=0;
        $blog->save();
        return $this->sendResponse([], 'Blog deleted successfully.');
    }

    public function saveBlogImage($id,Request $request){
        $validator = Validator::make($request->all(), [
            'image_files' => 'required',
            'image_files.*' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(),200);
        }

        $ufiles = [];
        if($request->hasfile('image_files')){
            // echo "here";exit;
            foreach($request->file('image_files') as $file){
                // print_r($file);exit;

                $filename = time().rand(1,100).'.'.$file->extension();
                $file->move(public_path('public/blog-images'), $filename);  
                $ufiles[] = $filename;  

                $blogImage = new BlogImage();
                $blogImage->blog_id = $id;
                $blogImage->image_url = $filename;
                $blogImage->is_primary_image = 0;
                $blogImage->is_active = 1;
                $blogImage->save();
            }
        }

        return $this->sendResponse([], 'File Has been uploaded successfully');
    }

    public function updateBlogImage($id,Request $request){
        $validator = Validator::make($request->all(), [
            'image_files' => 'required',
            'image_files.*' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(),200);
        }

        $ufiles = [];
        if($request->hasfile('image_files')){
            $blogImages = BlogImage::where('blog_id',$id)->get();   
            if(!empty($blogImages)){
                foreach($blogImages as $blogImage){
                    if(File::exists(public_path('public/blog-images/').$blogImage->image_url)){
                        File::delete(public_path('public/blog-images/').$blogImage->image_url);
                    }
                }
            }

            BlogImage::where('blog_id',$id)->delete();
            
            foreach($request->file('image_files') as $file){
                // print_r($file);exit;

                $filename = time().rand(1,100).'.'.$file->extension();
                $file->move(public_path('public/blog-images'), $filename);  
                $ufiles[] = $filename;  

                $blogImage = new BlogImage();
                $blogImage->blog_id = $id;
                $blogImage->image_url = $filename;
                $blogImage->is_primary_image = 0;
                $blogImage->is_active = 1;
                $blogImage->save();
            }
        }

        return $this->sendResponse([], 'File Has been uploaded successfully');
    }

    public function deleteBlogImage($id,Request $request){
        $blogImages = BlogImage::where('blog_id',$id)->get();   
        if(!empty($blogImages)){
            foreach($blogImages as $blogImage){
                if(File::exists(public_path('public/blog-images/').$blogImage->image_url)){
                    File::delete(public_path('public/blog-images/').$blogImage->image_url);
                }
            }
        }

        BlogImage::where('blog_id',$id)->delete();

        return $this->sendResponse([], 'File Has been deleted successfully');
    }

    public function setPrimaryImage(Request $request,$id){
        $blogImage = BlogImage::find($id);   
        $blogImage->is_primary_image=1;
        $blogImage->save();

        return $this->sendResponse([], 'File Has been deleted successfully');
    }

}