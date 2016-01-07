<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gate;
use App\Http\Requests;
use App\Post;
use App\Http\Controllers\Controller;

class PostsController extends Controller
{

    public function show($id)
    {
    	$post = Post::findOrFail($id);
    	// dd(Gate::denies('edit_post', $post));
    	if (Gate::denies('edit_post', $post)) {
    		return 'Khong duoc roi ban oi';
    	}

    	return $post->title;
    }
}
