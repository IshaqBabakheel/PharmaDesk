<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $activeUsers = User::where('status', 1)->count();
        $inactiveUsers = User::where('status', 0)->count();
        $rolesCount = Role::count();
        return view('users.index', compact('totalUsers', 'activeUsers', 'inactiveUsers', 'rolesCount'));
    }


    public function datatable(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $query = User::with('roles')->select('users.*');

        if ($filter === 'trashed') {
            $query->onlyTrashed();
        } elseif ($filter === 'active') {
            $query->whereNull('deleted_at');
        }
        
        return DataTables::eloquent($query)

            ->addIndexColumn()

            ->editColumn('status', function ($row) {

                return $row->status

                    ? '<span class="badge bg-success">Active</span>'

                    : '<span class="badge bg-danger">Inactive</span>';
            })

            ->addColumn('photo', function ($row) {

                if ($row->profile_photo) {

                    return '<img
                                src="' . asset('storage/' . $row->profile_photo) . '"
                                width="40"
                                height="40"
                                class="rounded-circle"
                            >';
                }

                return '<div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                            style="width:40px;height:40px;">
                            ' . strtoupper(substr($row->name,0,1)) . '
                        </div>';
            })

            ->addColumn('roles', function ($row) {

                return $row->roles

                    ->pluck('name')

                    ->map(function ($role) {

                        return '<span class="badge bg-primary me-1">' . $role . '</span>';
                    })

                    ->implode(' ');
            })

            ->addColumn('created_by', function ($row) {
            // If trashed, show who deleted it
                if ($row->trashed()) {
                    return $row->deleter?->name ?? 'System';
                }
                return $row->creator?->name ?? '-';
            }) 
            
            ->addColumn('action', function ($row) {
                $istrashed = $row->trashed();
                return view('components.action-dropdown', [
                    'row' => $row,
                    'module' => 'users',
                    'show' => true,
                    'edit' => !$istrashed,
                    'delete' => !$istrashed,
                    'restore' => $istrashed,
                    'forceDelete' => $istrashed
                ]);
            })

            ->rawColumns([
                'photo',
                'status',
                'roles',
                'action',
            ])

            ->make(true);
    }


    public function show(User $user)
    {
        $user->load('roles');

        return view('users.show', compact('user'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();

        return view('users.create', compact('roles'));
    }


    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('profile_photo')) {

            $data['profile_photo'] = $request
                ->file('profile_photo')
                ->store('users', 'public');
        }

        $data['password'] = Hash::make($request->password);

        $data['created_by'] = auth()->id();

        $user = User::create($data);

        $user->syncRoles($request->roles);

        return redirect()

            ->route('users.index')

            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();

        $user->load('roles');

        return view(

            'users.edit',

            compact(

                'user',

                'roles'

            )

        );
    }

    public function update(
        UpdateUserRequest $request,
        User $user
    ) {
        $data = $request->validated();

        if ($request->hasFile('profile_photo')) {

            if ($user->profile_photo) {

                Storage::disk('public')

                    ->delete($user->profile_photo);
            }

            $data['profile_photo'] = $request
                ->file('profile_photo')
                ->store('users', 'public');
        }

        if (!empty($request->password)) {

            $data['password'] = Hash::make($request->password);
        } else {

            unset($data['password']);
        }

        $data['updated_by'] = auth()->id();

        $user->update($data);

        $user->syncRoles($request->roles);

        return redirect()

            ->route('users.index')

            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {

            return back()->with(
                'error',
                'You cannot delete your own account.'
            );
        }

        if ($user->hasRole('Super Admin')) {

            $superAdmins = User::role('Super Admin')->count();

            if ($superAdmins <= 1) {

                return back()->with(
                    'error',
                    'The last Super Admin cannot be deleted.'
                );
            }

            return back()->with(
                'error',
                'Super Admin accounts cannot be deleted.'
            );
        }

        $user->update([

            'deleted_by' => auth()->id(),

        ]);

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'User deleted successfully.'
            );
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()

            ->findOrFail($id);

        $user->restore();

        return back()

            ->with('success', 'User restored successfully.');
    }

    public function forceDelete($id)
    {
        $user = User::onlyTrashed()
            ->findOrFail($id);

        if ($user->hasRole('Super Admin')) {

            $superAdmins = User::role('Super Admin')->count();

            if ($superAdmins <= 1) {

                return back()->with(
                    'error',
                    'The last Super Admin cannot be permanently deleted.'
                );
            }

            return back()->with(
                'error',
                'Super Admin accounts cannot be permanently deleted.'
            );
        }

        if ($user->profile_photo) {

            Storage::disk('public')
                ->delete($user->profile_photo);
        }

        $user->forceDelete();

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'User permanently deleted.'
            );
    }
}
