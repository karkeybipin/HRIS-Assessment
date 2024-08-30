<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Post;

use Illuminate\Support\Facades\Auth;


class AdminController extends Controller
{
    public function post_page()
    {
        return view('admin.post_page');
    }



    public function add_post(Request $request)
    {
        $user = Auth()->user();
        $userid = $user->id;
        $name = $user->name;
        $usertype = $user->usertype;


        $post = new Post;
        $post->title = $request->title;
        $post->description = $request->description;
        $post->post_status = 'active';

        $post->user_id = $userid;
        $post->name = $name;
        $post->usertype = $usertype;


        // ////////////////////////////////////////////////////////////////////////////
        $image = $request->image;
        if ($image) {
            $imagename = time() . '.' . $image->getClientOriginalExtension();
            $request->image->move('postimage', $imagename);
            $post->image = $imagename;
        }

        // ////////////////////////////////////////////////////////////////////////////

        $post->save();
        return redirect()->back()->with('message', 'Post added successfully!!!');
    }



    // public function show_post()
    // {
    //     $post = Post::all();
    //     return view('admin.show_post', compact('post'));
    // }
    public function index()
    {
        $post = Post::all(); // Retrieve all posts
        return view('home.service', compact('post')); // Pass $post to the view
    }

    public function show_post()
    {
        $post = Post::all();
        return view('admin.show_post', compact('post'));
    }


    public function delete_post($id)
    {
        $post = Post::find($id);
        $post->delete();
        return redirect()->back()->with('message', 'Post deleted successfully!!!');
    }


    public function edit_page($id)
    {
        $post = Post::find($id);
        return view('admin.edit_page', compact('post'));
    }
    public function update_post(Request $request, $id)
    {
        $post = Post::find($id);
        $post->title = $request->title;
        $post->description = $request->description;

        $image = $request->image;
        if ($image) {
            $imagename = time() . '.' . $image->getClientOriginalExtension();
            $request->image->move('postimage', $imagename);
            $post->image = $imagename;
        }

        $post->save();
        return redirect()->back();
    }

    public function FormsDownload()
    {
        return view('admin.FormsDownload');
    }



    public function showPosts()
    {
        $posts = Post::all();
        return view('service', compact('posts'));
    }
    // In your PostController.php

    public function deletePost($id)
    {
        $post = Post::findOrFail($id);
        if ($post->image) {
            File::delete(public_path('postimage/' . $post->image));
        }
        $post->delete();
        return redirect()->back()->with('message', 'Survey deleted successfully!');
    }
    // In your PostController.php

    public function addPost(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->description = $request->description;
        $post->usertype = auth()->user()->usertype; // Example of getting the user type

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('postimage'), $imageName);
            $post->image = $imageName;
        }

        $post->save();

        return redirect()->back()->with('message', 'Survey added successfully!');
    }
}

// {
//     // public function add_post(Request $request)
//     // {
//     //     $post = new Post;

//     //     // Assign title and description
//     //     $post->title = $request->title;
//     //     $post->description = $request->description;

//     //     // Check if an image is uploaded
//     //     if ($request->hasFile('image')) {
//     //         $image = $request->file('image');
//     //         $imageName = time() . '.' . $image->getClientOriginalExtension();
//     //         $image->move(public_path('images'), $imageName);
//     //         $post->image = $imageName;
//     //     }

//     //     $post->save();

//     //     return redirect()->back()->with('success', 'Post added successfully');
//     // }
// }`
