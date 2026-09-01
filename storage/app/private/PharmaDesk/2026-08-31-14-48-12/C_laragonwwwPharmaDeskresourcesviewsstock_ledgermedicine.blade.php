@extends('layouts.app')

@section('title', 'Medicine Stock Ledger')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                {{ $medicine->name }}
            </h3>

            <p class="text-muted mb-0">
                Stock Ledger by Batch
            </p>

        </div>

        <a
            href="{{ route('stock-ledger.index') }}"
            class="btn btn-secondary"
        >
            <i class="fas fa-arrow-left me-1"></i>
            Back
        </a>

    </div>


    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Batch Movement
            </h5>

        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Batch
                            </th>

                            <th>
                                Expiry
                            </th>

                            <th class="text-end">
                                Current Stock
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @php
                            $batches =
                                app(
                                    \App\Services\StockService::class
                                )
                                ->getAvailableBatches(
                                    $medicine->id
                                );
                        @endphp


                        @forelse ($batches as $batch)

                            <tr>

                                <td>
                                    {{ $batch->batch_number ?? '-' }}
                                </td>

                                <td>
                                    {{ $batch->expiry_date?->format('d M Y') ?? '-' }}
                                </td>

                                <td class="text-end fw-bold">
                                    {{ number_format(
                                        $batch->available_quantity,
                                        0
                                    ) }}
                                </td>

                                <td>

                                    <a
                                        href="{{ route(
                                            'stock-ledger.batch',
                                            [
                                                'medicineId' => $medicine->id,
                                                'batchNumber' => $batch->batch_number
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-info"
                                    >
                                        <i class="fas fa-eye"></i>
                                        Ledger
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="4"
                                    class="text-center text-muted py-4"
                                >
                                    No available batches found.
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