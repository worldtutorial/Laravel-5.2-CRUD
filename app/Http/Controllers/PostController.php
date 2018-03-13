<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Validator;
use App\Http\Requests\PostCreateRequest;
use App\Post;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (View::exists('posts.index')) {
            $search = $request->get('search');
            //dd($search);
            //$posts = Post::select('title','description','id')->simplePaginate(4);
            $posts = Post::select('title','description','id','deleted_at')->withTrashed();
            if(isset($search) && $search != null){
                $posts = $posts->where('title','LIKE',"%{$search}%");
                $posts = $posts->orWhere('description','LIKE',"%{$search}%");
            }
            $posts = $posts->paginate(4);
            return view('posts.index',compact('posts'));
        }else{// true
            return redirect('/');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (View::exists('posts.create')) {
            return view('posts.create');
        }else{// true
            return redirect('/');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostCreateRequest $request)
    {
        $input = $request->all();
        $post = new Post();//Create new database
        $post->title = $input['title'];
        $post->description = $input['description'];
        if($post->save()){
            return redirect()->back()->with('message_success',"Insert Successfully");
        }else{
            return redirect()->back()->with('message_error',"Sorry Please try again.");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (View::exists('posts.edit')) {
            $post = Post::find($id);
            return view('posts.edit',compact('post'));
        }else{// true
            return redirect('/');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $post = Post::find($id);
        $post->title = $input['title'];
        $post->description = $input['description'];
        if($post->save()){
            return redirect('admin/post')->with('message_success',"update Successfully");
        }else{
            return redirect('admin/post')->with('message_success',"Sorry please try again");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        if($post->delete()){// this Is Soft Delete
            return redirect()->back()->with('message_success',"Delete Successfully");
        }else{
            return redirect()->back()->with('message_success',"Sorry please try again");
        }
        // Force Delete
        //$post = Post::find($id);
        /*$post = Post::find($id);
        if($post->forceDelete()){// this Is Soft Delete
            return redirect()->back()->with('message_success',"Delete Successfully");
        }else{
            return redirect()->back()->with('message_success',"Sorry please try again");
        }*/
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(Request $request,$id){
        $post = Post::onlyTrashed()->find($id);
        if($post->restore()){
            return redirect()->back()->with('message_success',"Restore Successfully");
        }else{
            return redirect()->back()->with('message_success',"Sorry please try again");
        }
    }
}
