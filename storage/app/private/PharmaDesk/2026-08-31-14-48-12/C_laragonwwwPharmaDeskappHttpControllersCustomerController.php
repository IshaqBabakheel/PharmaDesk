<?php

namespace App\Http\Controllers;


use App\Models\Customer;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{


    // public function __construct()
    // {

    //     $this->authorizeResource(Customer::class,'customer');

    // }

    public function index()
    {
        $this->authorize('viewAny', Customer::class);

        $totalCustomers = Customer::count();
        $creditCustomers = Customer::where('credit_limit', '>', 0)->count();
        $corporateCustomers = Customer::where('customer_type', 'corporate')->count();
        $regularCustomers = Customer::where('customer_type', 'regular')->count();

        return view('customers.index', 
        compact('totalCustomers', 'creditCustomers', 'corporateCustomers', 'regularCustomers'));
    }



    public function datatable(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $query = Customer::with('creator')->select('customers.*');

        // filters
        if ($filter === 'trashed') {
            $query->onlyTrashed();
        } elseif ($filter === 'active') {
            $query->whereNull('deleted_at');
        }
        
        return DataTables::eloquent($query)

            ->addIndexColumn()

            ->addColumn(
                'type',
                function ($row) {

                    return $row->type_badge;
                }
            )

            ->addColumn(
                'balance',
                function ($row) {

                    return $row->balance_badge;
                }
            )

            ->addColumn(
                'sales',
                function ($row) {

                    return number_format($row->sales()->sum('grand_total'), 2);
                }
            )

            ->addColumn(
                'due',
                function ($row) {
                    return number_format($row->sales()->sum('due_amount'), 2);
                }
            )

            ->addColumn('created_by', function ($row) {
                // If trashed, show who deleted it
                if ($row->trashed()) {
                    return $row->deleter?->name ?? 'System';
                }
                return $row->creator?->name ?? '-';
            })

            ->addColumn(
                'action',
                function ($row) {
                    // Determine if supplier is trashed
                    $isTrashed = $row->trashed();
                    return view('components.action-dropdown', [
                        'row' => $row,
                        'module' => 'customers',
                        'show' => true,
                        'edit' => !$isTrashed, // Only show edit for non-trashed
                        'delete' => !$isTrashed, // Only show delete for non-trashed
                        'restore' => $isTrashed, // Show restore for trashed
                        'forceDelete' => $isTrashed, // Show force delete for trashed
                    ]);
                }
            )

            ->rawColumns([

                'type',

                'balance',

                'action'

            ])

            ->make(true);
    }


    public function show(Customer $customer)
    {


        $customer->load([

            'sales.items.medicine',

            'creator'

        ]);




        return view(
            'customers.show',
            compact('customer')
        );


    }


    public function create()
    {

        return view('customers.create');

    }

    public function edit(Customer $customer)
    {


        return view(
            'customers.edit',
            compact('customer')
        );


    }


    public function store(StoreCustomerRequest $request)
    {


        Customer::create([

            ...$request->validated(),


            'created_by'=>
                Auth::id()


        ]);


        return redirect()->route('customers.index')->with('success', 'Customer created successfully');

    }


    public function update(UpdateCustomerRequest $request, Customer $customer)
    {

        $customer->update([

            ...$request->validated(),

            'updated_by'=>
                Auth::id()

        ]);


        return redirect()
            ->route('customers.index')
            ->with(
                'success',
                'Customer updated successfully'
            );
    }



    public function destroy(Customer $customer)
    {


        /*
        |--------------------------------------------------------------------------
        | Prevent deleting walk-in customer
        |--------------------------------------------------------------------------
        */


        if($customer->customer_type === 'walk_in')
        {

            return back()
            ->with(
                'error',
                'Walk-in customer cannot be deleted'
            );

        }

        $customer->delete();

        return back()
            ->with(
                'success',
                'Customer deleted successfully'
            );
    }



    public function restore($id)
    {
        $customer = Customer::withTrashed()->findOrFail($id);

        $customer->restore();

        return back()
            ->with(
                'success',
                'Customer restored successfully'
            );
    }


    public function forceDelete($id)
    {
        $customer = Customer::withTrashed()->findOrFail($id);

        if($customer->sales()->exists())
        {

            return back()
            ->with(
                'error',
                'Customer has sales records and cannot be permanently deleted'
            );

        }

        $customer->forceDelete();

        return back()
            ->with(
                'success',
                'Customer permanently deleted'
            );
    }

}
