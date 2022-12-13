<?php

namespace App\Http\Controllers;

use App\Http\Request\Login\LoginRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class LoginController extends Controller
{
    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function attemptLogin(LoginRequest $request)
    {
        try {
            $user = User::whereEmail($request->get('email'))->first();

            if (is_null($user)) {
                return response()->json([
                    "error" => 'El usuario no es correcto'
                ], Response::HTTP_BAD_REQUEST);
            } else {
                $user->toArray();
            }

            if (Hash::check($request->get('password'), $user['password'])) {
                Passport::personalAccessTokensExpireIn(Carbon::now()->addHour(2));
                Auth::attempt([
                    "email" => $request->get('email'),
                    "password" => $request->get('password')
                ]);

                $token = Auth::user()->createToken($request->get('email'))->accessToken;

                return response()->json([
                    "name" => $user['name'],
                    "token" => $token,
                    "id" => $user['id'],
                    "role" => $user["role_id"]
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    "error" => 'Contraseña incorrecta'
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

        } catch (Throwable $throwable) {
            return response()->json([
                "title" => 'Error interno del sistema',
                "error" => $throwable->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
