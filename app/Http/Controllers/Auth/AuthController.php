<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Posts;

class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param Request $request
     * @param String  userName
     * @param String  email
     * @param String  password
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'userName' => 'required|string',
            'email'    => 'required|string|email|unique:users',
            'password' => 'required|string'
        ],
        [
            'required' => 'Невверно введено :attribute',
            'unique'   => 'Данный  :attribute уже занят',
            'email'    => 'Некорректная электронная почта'
        ]);

        $user = new User([
            'name'     => $request->userName,
            'email'    => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $user->save();

        return response()->json([
            'success' => true,
            'id'      => $user->id,
            'name'    => $user->name,
            'email'   => $user->email,
        ], 201);
    }


    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized Access, please confirm credentials or verify your email'
            ], 401);

        $user = $request->user();

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->save();
        return response()->json([
            'success' => true,
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ], 201);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function command(Request $request)
    {
        libxml_use_internal_errors(true);
        $domDoc = new \DOMDocument();

        $startPage = 0;
        while (true) {
            $domDoc->loadHTMLFile("https://realt.by/rent/flat-for-day/?page=$startPage");
            $xpath = new \DOMXPath($domDoc);

            $postTitles = $xpath->evaluate("//div[@class='media-body']");
            $postPrice = $xpath->evaluate("//span[@class='price-byr']");
            $postCreatedAt = $xpath->evaluate("//p[@class='fl f11 grey']");
            $postStatus = $xpath->evaluate("//div[@class='bd-item-right-top']");
            $postId = $xpath->evaluate("//div[@class='bd-item-right-top']/p[@class='fr f11 grey']");
            $postImages = $xpath->evaluate('//div[@class="bd-item-left"]/div[@class="bd-item-left-top"]/a/img');
            $postMainContent = $xpath->evaluate("//div[@class='bd-item-right-center']");

            if ($postTitles->length == 0) break;

            $data = [];
            for ($i = 0; $i < $postTitles->length; $i++) {
                $currentPostId = ltrim($postId[$i]->nodeValue, '#');
                if (Posts::where('post_id', '=', $currentPostId)->count() > 0) continue;

                array_push($data, [
                    'title'        => $postTitles[$i]->nodeValue,
                    'main_content' => $domDoc->saveHTML($postMainContent[$i]),
                    'price'        => str_replace(' руб/сутки', '', $postPrice[$i]->nodeValue),
                    'image_path'   => $postImages[$i]->attributes[1]->value,
                    'post_status'  => $domDoc->saveHTML($postStatus[$i]),
                    'post_id'      => $currentPostId,
                    'created_at'   => \Carbon\Carbon::parse(trim($postCreatedAt[$i]->nodeValue, ' '))->format('Y-m-d')
                ]);
            }
            Posts::insert($data);
            echo "Page $page was parsed \n";
            $page++;
        }

        libxml_clear_errors();
    }
}
