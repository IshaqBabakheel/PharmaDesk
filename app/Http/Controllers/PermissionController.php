<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Policies\PermissionPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
// use Spatie\Permission\Models\Permission;
use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    /**
     * Display permissions listing.
     */
    // public function index()
    // {
    //     return view('permissions.index');
    // }
    public function index()
    {
        $totalPermissions = Permission::count();

        $assignedPermissions = Permission::has('roles')->count();

        $unassignedPermissions = Permission::doesntHave('roles')->count();

        $totalModules = Permission::all()

            ->map(function ($permission) {

                return explode('.', $permission->name)[0];

            })

            ->unique()

            ->count();

        return view('permissions.index', compact(

            'totalPermissions',

            'assignedPermissions',

            'unassignedPermissions',

            'totalModules'

        ));
    }

    /**
     * Datatable.
     */
    public function datatable(Request $request)
    {
        $filter = $request->get('filer', 'all');
        $query = Permission::withCount('roles')->select('permissions.*');

        if ($filter === 'trashed') {
            $query->onlyTrashed();
        } elseif ($filter === 'active') {
            $query->whereNull('deleted_at');
        }
        
        return DataTables::eloquent($query)

            ->addIndexColumn()

            ->addColumn('module', function ($row) {

                return Str::headline(
                    explode('.', $row->name)[0]
                );

            })

            ->addColumn('permission_action', function ($row) {

                return Str::headline(
                    explode('.', $row->name)[1] ?? '-'
                );

            })

            ->editColumn('guard_name', function ($row) {

                return '<span class="badge bg-info">'
                    . strtoupper($row->guard_name) .
                    '</span>';

            })

            ->addColumn('roles_count', function ($row) {

                return '<span class="badge bg-success">'
                    . $row->roles_count .
                    '</span>';

            })

            ->addColumn('created_by', function ($row) {
                if($row->trashed()){
                    return $row->deleter?->name ?? 'System';
                }
                return $row->creator?->name ?? '-';
            })

            ->addColumn('action', function ($row) {
                $isTrashed = $row->trashed();
                return view('components.action-dropdown', [
                    'row' => $row,
                    'module' => 'permissions',
                    'show' => true,
                    'edit' => !$isTrashed,
                    'delete' => !$isTrashed,
                    'restore' => $isTrashed,
                    'forceDelete' => $isTrashed
                ]);

            })

            ->rawColumns([

                'guard_name',

                'roles_count',

                'action',

            ])

            ->make(true);
    }

    /**
     * Show permission.
     */
    public function show(Permission $permission)
    {
        $permission->load('roles');

        return view('permissions.show', compact('permission'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        // $actions = $this->availableActions();

        return view('permissions.create');
    }

    /**
     * Return available actions for a permission module.
     */
    public function actions(Request $request): JsonResponse
    {
        $module = $request->get('module');

        if (! $module) {
            return response()->json([
                'success' => false,
                'message' => 'Module is required.',
                'actions' => [],
            ], 422);
        }

        $modules = config('permission.modules', []);

        if (! array_key_exists($module, $modules)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid module.',
                'actions' => [],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'module' => $module,
            'actions' => $modules[$module],
        ]);
    }

    /**
     * Store permission.
     */
    public function store(StorePermissionRequest $request)
    {
        $permissionName = $this->generatePermissionName(
            $request->module,
            $request->action
        );

        if (
            Permission::where('name', $permissionName)
                ->where('guard_name', 'web')
                ->exists()
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'module' => 'This permission already exists.',
                ]);
        }

        Permission::create([
            'name' => $permissionName,
            'guard_name' => 'web',
        ]);

        return redirect()
            ->route('permissions.index')
            ->with('success', 'Permission created successfully.');
    }


    /**
     * Show edit form.
     */
    public function edit(Permission $permission)
    {
        $parts = explode('.', $permission->name);

        $module = $parts[0] ?? '';

        $action = $parts[1] ?? '';

        $actions = $this->availableActions();

        return view(
            'permissions.edit',
            compact(
                'permission',
                'module',
                'action',
                'actions'
            )
        );
    }

    /**
     * Update permission.
     */
    public function update(UpdatePermissionRequest $request, Permission $permission) 
    {

        $permissionName = $this->generatePermissionName(

            $request->module,

            $request->action

        );

        $exists = Permission::where(

            'name',

            $permissionName

        )

            ->where('id', '!=', $permission->id)

            ->exists();

        if ($exists) {

            return back()

                ->withInput()

                ->withErrors([

                    'module' => 'Permission already exists.'

                ]);

        }

        $permission->update([

            'name' => $permissionName,

        ]);

        return redirect()

            ->route('permissions.index')

            ->with(

                'success',

                'Permission updated successfully.'

            );
    }

    /**
     * Delete permission.
     */
    public function destroy(Permission $permission)
    {
        if ($permission->roles()->count()) {

            return back()->with(

                'error',

                'This permission is assigned to one or more roles.'

            );

        }

        $permission->delete();

        return redirect()

            ->route('permissions.index')

            ->with(

                'success',

                'Permission deleted successfully.'

            );
    }

    /**
     * Generate permission name.
     *
     * Example:
     * medicines + view
     * =
     * medicines.view
     */
    private function generatePermissionName(string $module, string $action): string {

        return Str::slug($module) . '.' . Str::slug($action);

    }

    /**
     * Available actions.
     */
    // private function availableActions(): array
    // {
    //     return [

    //         'view',

    //         'create',

    //         'edit',

    //         'delete',

    //         'restore',

    //         'force-delete',

    //         'import',

    //         'export',

    //         'print',

    //     ];
    // }
}