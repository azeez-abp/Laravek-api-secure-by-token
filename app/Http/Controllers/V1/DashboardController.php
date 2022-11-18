<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

use function Pest\Laravel\json;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = $request->user();

        return view('dashboard', ['tokens' => $user->tokens]);
    }

    public function showToken()
    {
        return view('token-create');
    }

    public function createToken(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $tokenName  = $request->post('name');
        $user  = $request->user();
        $token   = $user->createToken($tokenName);
        return view('token-show', [
            'tokenName' => $tokenName,
            'tokens' => $token->plainTextToken
        ]);
    }

    public function deleteToken(PersonalAccessToken $token, Request $request, $data)
    {
        // print_r($token);

        //  print_r($token->all());
        foreach (json_decode($data) as $key) {

            foreach ($token->all() as $t => $k) {
                if ($k->tokenable_id === $request->user()->id) {
                    $token::where(['id' => $key])->delete();
                } else {
                    return response()->json(['err' => 'unautheticated use']);
                }
            }
        }
        //  return redirect('dashboard');
        return response()->json(['suc' => 'items delted']);
    }
}
