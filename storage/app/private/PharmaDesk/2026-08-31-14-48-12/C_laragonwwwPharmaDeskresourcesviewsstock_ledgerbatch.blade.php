@extends('layouts.app')

@section('title', 'Batch Stock Ledger')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                {{ $medicine->name }}
            </h3>

            <p class="text-muted mb-0">
                Batch:
                <strong>
                    {{ $batchNumber }}
                </strong>
            </p>

        </div>


        <a
            href="{{ route(
                'stock-ledger.medicine',
                $medicine->id
            ) }}"
            class="btn btn-secondary"
        >
            <i class="fas fa-arrow-left me-1"></i>
            Back
        </a>

    </div>


    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Batch Movement History
            </h5>

        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table
                    class="table table-bordered table-hover align-middle"
                >

                    <thead class="table-light">

                        <tr>

                            <th>
                                Date
                            </th>

                            <th>
                                Transaction
                            </th>

                            <th>
                                Reference
                            </th>

                            <th class="text-end">
                                In
                            </th>

                            <th class="text-end">
                                Out
                            </th>

                            <th class="text-end">
                                Balance
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($ledger as $row)

                            <tr>

                                <td>
                                    {{ $row['date']?->format('d M Y') ?? '-' }}
                                </td>

                                <td>
                                    {{ $row['transaction'] }}
                                </td>

                                <td>
                                    {{ $row['reference'] ?? '-' }}
                                </td>

                                <td class="text-end text-success">
                                    {{ number_format(
                                        $row['in'],
                                        0
                                    ) }}
                                </td>

                                <td class="text-end text-danger">
                                    {{ number_format(
                                        $row['out'],
                                        0
                                    ) }}
                                </td>

                                <td class="text-end fw-bold">
                                    {{ number_format(
                                        $row['balance'],
                                        0
                                    ) }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center text-muted py-4"
                                >
                                    No stock movement found for this batch.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection