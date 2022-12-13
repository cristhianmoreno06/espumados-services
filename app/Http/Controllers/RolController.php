<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class RolController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index()
    {
        //$listRole = Role::orderBy('id', 'desc')->paginate(8);
        $listRole = Role::all();

        return response()->json([
            'code' => Response::HTTP_OK,
            'title' => 'Listado de roles',
            'message' => 'Se listaron los roles correctamente',
            "source" => $listRole,
        ], Response::HTTP_OK);
    }

    public function storeOrUpdateRol(Request $request): JsonResponse
    {
        try {
            $form = json_decode($request->get('form'));
            $getRole = $form->role;

            if (is_null($getRole->id)) {
                $role = new Role();
            } else {
                $role = Role::whereId($getRole->id)->first();
            }

            $role->name = $getRole->name;
            $role->screen_name = $getRole->screen_name;
            $role->save();

            if (!$role->save()) {
                return response()->json([
                    'code' => Response::HTTP_BAD_REQUEST,
                    'title' => 'Creación de rol',
                    'message' => 'Error al crear o actualizar el rol en el sistema',
                    "source" => $role,
                ], Response::HTTP_OK);
            }

            return response()->json([
                'code' => Response::HTTP_OK,
                'title' => 'Creación de rol',
                'message' => 'Creación o Actualización del rol correctamente en el sistema',
                "source" => $role,
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
        $listRole = Role::whereId($id)->first();

        return response()->json([
            'code' => Response::HTTP_OK,
            'title' => 'Listado de rol',
            'message' => 'Se listo el rol correctamente',
            "source" => $listRole,
        ], Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $role = Role::whereId($id)->first();
        $role->name = $request->get('name');
        $role->screen_name = $request->get('screen_name');
        $role->save();

        return response()->json([
            'code' => Response::HTTP_OK,
            'title' => 'Edición de rol',
            'message' => 'El rol se edito correctamente',
            "source" => $role,
        ], Response::HTTP_OK);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $role = Role::whereId($id)->delete();

        return response()->json([
            'code' => Response::HTTP_OK,
            'title' => 'Eliminación de rol',
            'message' => 'Se elimino el rol correctamente',
            "source" => $role,
        ], Response::HTTP_OK);
    }
}
