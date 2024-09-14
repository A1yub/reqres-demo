<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Reqres\Service\UserService;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $userService = new UserService();

        $users = $userService->getUsers(
            page: $request->get('page', 1),
            perPage: $request->get('perPage', 10),
        );

        return Inertia::render('Home')->with([
            'users' => UserResource::collection(collect($users->data))->additional([
                'pagination' => $users->pagination,
            ]),
        ]);
    }
}
