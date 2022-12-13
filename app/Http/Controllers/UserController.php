<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UserController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index()
    {
        //$listUser = User::orderBy('id', 'desc')->paginate(8);
        $listUser = User::orderBy('id', 'desc')
            /*->with('roles')*/
            ->get();

        return response()->json([
            'code' => Response::HTTP_OK,
            'title' => 'Listado de usuario',
            'message' => 'Se listaron los usuarios correctamente',
            "source" => $listUser,
        ], Response::HTTP_OK);
    }

    public function storeOrUpdateUser(Request $request): JsonResponse
    {
        try {
            $form = json_decode($request->get('form'));
            $getUser = $form->user;

            if (is_null($getUser->id)) {
                $user = new User();
            } else {
                $user = User::whereId($getUser->id)->first();
            }

            $user->name = $getUser->name;
            $user->email = $getUser->email;
            $user->identification = $getUser->identification;
            $user->password = $getUser->password;
            $user->role_id = $getUser->role_id;
            $user->save();

            if (!$user->save()) {
                return response()->json([
                    'code' => Response::HTTP_BAD_REQUEST,
                    'title' => 'Creación de usuario',
                    'message' => 'Error al crear o actualizar el usuario en el sistema',
                    "source" => $user,
                ], Response::HTTP_OK);
            }

            return response()->json([
                'code' => Response::HTTP_OK,
                'title' => 'Creación de usuario',
                'message' => 'Creación o Actualización del usuario correctamente en el sistema',
                "source" => $user,
            ], Response::HTTP_OK);

        }catch (Throwable $throwable) {
            return response()->json([
                "title" => 'Error interno del sistema',
                "error" => $throwable->getMessage(). ' linea = ' . $throwable->getLine()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $listUser = User::whereId($id)->first();

        return response()->json([
            'code' => Response::HTTP_OK,
            'title' => 'Listado de usuario',
            'message' => 'Se listo el usuario correctamente',
            "source" => $listUser,
        ], Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::whereId($id)->first();
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = $request->get('password');
        $user->role_id = $request->get('role_id');
        $user->save();

        return response()->json([
            'code' => Response::HTTP_OK,
            'title' => 'Edición de usuario',
            'message' => 'El usuario se edito correctamente',
            "source" => $user,
        ], Response::HTTP_OK);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $user = User::whereId($id)->delete();

        return response()->json([
            'code' => Response::HTTP_OK,
            'title' => 'Eliminación de usuario',
            'message' => 'Se elimino el usuario correctamente',
            "source" => $user,
        ], Response::HTTP_OK);
    }
}
