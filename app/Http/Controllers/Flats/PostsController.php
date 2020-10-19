<?php

namespace App\Http\Controllers\Flats;

use App\Http\Requests\PostsUpdateByIdRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FlatPost;

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
            $paginationData = FlatPost::paginate(self::PER_PAGE);
        } elseif ($sortPrice === 'DEFAULT') {
            $paginationData = FlatPost::orderBy('created_at', $sortDate)->paginate(self::PER_PAGE);
        } elseif ($sortDate === 'DEFAULT') {
            $paginationData = FlatPost::orderByRaw("LENGTH(flat_posts.price) $sortPrice, flat_posts.price $sortPrice")->paginate(self::PER_PAGE);
        } else {
            $paginationData = FlatPost::orderByRaw("LENGTH(flat_posts.price) $sortPrice, flat_posts.price $sortPrice, created_at $sortDate")->paginate(self::PER_PAGE);
        }

        $paginationData->appends([
            'sort-price' => 'DEFAULT',
            'sort-date'  => 'DEFAULT'
        ])->links();

        return response()->json($paginationData);
    }

    /**
     * Remove entity by id.
     *
     * @param Request $request
     * @param $postId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeById(Request $request, $postId)
    {
        FlatPost::destroy($postId);
        return response()->json('Запись удалена');
    }

    /**
     * Get entity by id.
     *
     * @param Request $request
     * @param $postId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getById(Request $request, $postId)
    {
        $post = FlatPost::find($postId, ['main_content', 'id', 'price', 'title']);
        return response()->json($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     */
    public function updateById(PostsUpdateByIdRequest $request)
    {
        FlatPost::find($request->postId)->update([
            'main_content' => $request->mainContent,
            'price'        => $request->price,
            'title'        => $request->title
        ]);
        return response()->json('Успешно обновлено');
    }
}
