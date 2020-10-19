<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Posts;

class PostsController extends Controller
{
    const PER_PAGE = 20;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        $sortPrice = $request->input('sort-price');
        $sortDate = $request->input('sort-date');

        if (($sortPrice === 'DEFAULT' || $sortPrice === null) && ($sortDate === 'DEFAULT' || $sortDate === null)) {
            $paginationData = Posts::paginate(self::PER_PAGE);
        } elseif ($sortPrice === 'DEFAULT') {
            $paginationData = Posts::orderBy('created_at', $sortDate)->paginate(self::PER_PAGE);
        } elseif ($sortDate === 'DEFAULT') {
            $paginationData = Posts::orderByRaw("LENGTH(posts.price) $sortPrice, posts.price $sortPrice")->paginate(self::PER_PAGE);
        } else {
            $paginationData = Posts::orderByRaw("LENGTH(posts.price) $sortPrice, posts.price $sortPrice, created_at $sortDate")->paginate(self::PER_PAGE);
        }

        $paginationData->appends([
            'sort-price' => 'DEFAULT',
            'sort-date'  => 'DEFAULT'
        ])->links();

        return response()->json($paginationData);
    }

    public function removeById(Request $request, $postId)
    {
        Posts::destroy($postId);
        return response()->json('Запись удалена');
    }

    public function getById(Request $request, $postId)
    {
        $post = Posts::find($postId);
        return response()->json($post);
    }
}
