<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
// use Spatie\Permission\Models\Role;
use App\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    /**
     * Display roles listing.
     */
    public function index()
    {
        return view('roles.index');
    }

    /**
     * Datatable.
     */
    public function datatable(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $query = Role::with(['permissions', 'users',]);

        if ($filter === 'trashed') {
            $query->onlyTrashed();
        } elseif ($filter === 'active') {
            $query->whereNull('deleted_at');
        }
        
        return DataTables::eloquent($query)

            ->addIndexColumn()

            ->addColumn('permissions_count', function ($row) {

                return '<span class="badge bg-primary">'
                        .$row->permissions->count().
                        '</span>';

            })

            ->addColumn('users_count', function ($row) {

                return '<span class="badge bg-success">'
                        .$row->users->count().
                        '</span>';

            })

            ->editColumn('guard_name', function ($row) {

                return '<span class="badge bg-info">' .
                    strtoupper($row->guard_name) .
                    '</span>';

            })

            ->editColumn('created_by', function ($row) {
                if($row->trashed()){
                    return $row->deletor?->name ?? 'System';
                }
                return $row->creator?->name ?? '-';
            })

            ->addColumn('action', function ($row) {
                $isTrashed = $row->trashed();
                return view('components.action-dropdown',[
                    'row' => $row,
                    'module' => 'roles',
                    'view' => true,
                    'edit' => !$isTrashed,
                    'delete' => !$isTrashed,
                    'restore' => $isTrashed,
                    'forceDelete' => $isTrashed
                ]);

            })

            ->rawColumns([
                'guard_name',
                'permissions_count',
                'users_count',
                'action',
            ])

            ->make(true);
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $permissions = Permission::orderBy('name')
            ->get()
            ->groupBy(function ($permission) {

                return explode('.', $permission->name)[0];

            });

        return view(
            'roles.create',
            compact('permissions')
        );
    }

    /**
     * Store role.
     */
    public function store(StoreRoleRequest $request)
    {
        $role = Role::create([

            'name' => $request->name,

            'guard_name' => 'web',

        ]);

        $role->syncPermissions(
            $request->permissions ?? []
        );

        return redirect()

            ->route('roles.index')

            ->with(
                'success',
                'Role created successfully.'
            );
    }

    /**
     * Display role.
     */
    public function show(Role $role)
    {
        $role->load([
            'permissions',
            'users',
        ]);

        $permissions = $role->permissions

            ->groupBy(function ($permission) {

                return explode('.', $permission->name)[0];

            });

        return view(
            'roles.show',
            compact(
                'role',
                'permissions'
            )
        );
    }

    /**
     * Edit role.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('name')

            ->get()

            ->groupBy(function ($permission) {

                return explode('.', $permission->name)[0];

            });

        return view(
            'roles.edit',
            compact(
                'role',
                'permissions'
            )
        );
    }

    /**
     * Update role.
     */
    public function update(
        UpdateRoleRequest $request,
        Role $role
    ) {

        $role->update([

            'name' => $request->name,

        ]);

        $role->syncPermissions(
            $request->permissions ?? []
        );

        return redirect()

            ->route('roles.index')

            ->with(
                'success',
                'Role updated successfully.'
            );
    }

    /**
     * Delete role.
     */
    public function destroy(Role $role)
    {
        if ($role->name === 'Super Admin') {

            return back()->with(

                'error',

                'Super Admin role cannot be deleted.'

            );

        }

        if ($role->users()->count()) {

            return back()->with(

                'error',

                'This role is assigned to one or more users.'

            );

        }

        $role->delete();

        return redirect()

            ->route('roles.index')

            ->with(
                'success',
                'Role deleted successfully.'
            );
    }
}