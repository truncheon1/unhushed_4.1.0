<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\DataTables\RolesDataTable;
use App\Models\Roles;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getIndex(RolesDataTable $rolesDataTable)
    {
        return $rolesDataTable->render('roles.index');
    }

    public function getCreate()
    {
        return view('roles.create');
    }

    public function postCreate()
    {
        request()->validate(Roles::rules());

        Roles::query()->create(request()->all());

        return response()->json([
            'dismiss_modal' => true,
            'reload_table' => true,
        ]);
    }

    public function getShow(Roles $roles)
    {
        return view('roles.show', compact('roles'));
    }

    public function getEdit(Roles $roles)
    {
        return view('roles.edit', compact('roles'));
    }

    public function patchEdit(Roles $roles)
    {
        request()->validate(Roles::rules($roles));

        $roles->update(request()->all());

        return response()->json([
            'dismiss_modal' => true,
            'reload_table' => true,
        ]);
    }

    public function deleteDestroy(Roles $roles)
    {
        $roles->forceDelete();

        return response()->json([
            'reload_table' => true,
        ]);
    }
}
