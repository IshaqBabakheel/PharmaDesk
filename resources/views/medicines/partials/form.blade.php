<form action="{{ isset($medicine) ? route('medicines.update', $medicine) : route('medicines.store') }}"
    method="POST" enctype="multipart/form-data">

    @csrf

    @isset($medicine)
        @method('PUT')
    @endisset


    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">

                <i class="fas fa-capsules text-primary me-2"></i>

                {{ isset($medicine) ? 'Edit Medicine' : 'Add New Medicine' }}

            </h3>

            <p class="text-muted mb-0">

                Manage medicine information, pricing and inventory.

            </p>

        </div>

        <nav aria-label="breadcrumb">

            <ol class="breadcrumb justify-content-end mb-0">

                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">
                        Dashboard
                    </a>
                </li>

                <li class="breadcrumb-item">
                    Master Data
                </li>

                <li class="breadcrumb-item">
                    <a href="{{ route('medicines.index') }}">
                        Medicines
                    </a>
                </li>

                <li class="breadcrumb-item active" aria-current="page">

                    {{ isset($medicine) ? 'Edit' : 'Create' }}

                </li>

            </ol>

        </nav>

    </div>



    <div class="row">

        {{-- ===================================================== --}}
        {{-- Basic Information --}}
        {{-- ===================================================== --}}

        <div class="col-lg-8 mb-4">

            <div class="card shadow-sm">

                <div class="card-header">

                    <h5 class="mb-0">

                        <i class="fas fa-pills text-primary me-2"></i>

                        Basic Information

                    </h5>

                </div>

                <div class="card-body">

                    <div class="row">

                        {{-- Medicine Name --}}

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Medicine Name
                                <span class="text-danger">*</span>

                            </label>

                            <input type="text" name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $medicine->name ?? '') }}" placeholder="Enter medicine name">

                            @error('name')
                                <div class="invalid-feedback">

                                    {{ $message }}

                                </div>
                            @enderror

                        </div>



                        {{-- Generic Name --}}

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Generic Name

                            </label>

                            <input type="text" name="generic_name"
                                class="form-control @error('generic_name') is-invalid @enderror"
                                value="{{ old('generic_name', $medicine->generic_name ?? '') }}"
                                placeholder="Generic name">

                            @error('generic_name')
                                <div class="invalid-feedback">

                                    {{ $message }}

                                </div>
                            @enderror

                        </div>



                        {{-- Category --}}

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Category

                                <span class="text-danger">*</span>

                            </label>

                            <select name="medicine_category_id"
                                class="form-select @error('medicine_category_id') is-invalid @enderror">

                                <option value="">

                                    Select Category

                                </option>

                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('medicine_category_id', $medicine->medicine_category_id ?? '') == $category->id)>

                                        {{ $category->name }}

                                    </option>
                                @endforeach

                            </select>

                            @error('medicine_category_id')
                                <div class="invalid-feedback">

                                    {{ $message }}

                                </div>
                            @enderror

                        </div>



                        {{-- Medicine Type --}}

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Medicine Type

                                <span class="text-danger">*</span>

                            </label>

                            <select name="medicine_type_id"
                                class="form-select @error('medicine_type_id') is-invalid @enderror">

                                <option value="">

                                    Select Medicine Type

                                </option>

                                @foreach ($types as $type)
                                    <option value="{{ $type->id }}" @selected(old('medicine_type_id', $medicine->medicine_type_id ?? '') == $type->id)>

                                        {{ $type->name }}

                                    </option>
                                @endforeach

                            </select>

                            @error('medicine_type_id')
                                <div class="invalid-feedback">

                                    {{ $message }}

                                </div>
                            @enderror

                        </div>



                        {{-- Manufacturer --}}

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Manufacturer

                                <span class="text-danger">*</span>

                            </label>

                            <select name="manufacturer_id"
                                class="form-select @error('manufacturer_id') is-invalid @enderror">

                                <option value="">

                                    Select Manufacturer

                                </option>

                                @foreach ($manufacturers as $manufacturer)
                                    <option value="{{ $manufacturer->id }}" @selected(old('manufacturer_id', $medicine->manufacturer_id ?? '') == $manufacturer->id)>

                                        {{ $manufacturer->name }}

                                    </option>
                                @endforeach

                            </select>

                            @error('manufacturer_id')
                                <div class="invalid-feedback">

                                    {{ $message }}

                                </div>
                            @enderror

                        </div>



                        {{-- Unit --}}

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Unit

                                <span class="text-danger">*</span>

                            </label>

                            <select name="unit_id" class="form-select @error('unit_id') is-invalid @enderror">

                                <option value="">

                                    Select Unit

                                </option>

                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}" @selected(old('unit_id', $medicine->unit_id ?? '') == $unit->id)>

                                        {{ $unit->name }}

                                    </option>
                                @endforeach

                            </select>

                            @error('unit_id')
                                <div class="invalid-feedback">

                                    {{ $message }}

                                </div>
                            @enderror

                        </div>



                        {{-- Description --}}

                        <div class="col-12">

                            <label class="form-label">

                                Description

                            </label>

                            <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror"
                                placeholder="Additional information...">{{ old('description', $medicine->description ?? '') }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">

                                    {{ $message }}

                                </div>
                            @enderror

                        </div>

                    </div>

                </div>

            </div>

        </div>
        {{-- ===================================================== --}}
        {{-- Right Sidebar --}}
        {{-- ===================================================== --}}

        <div class="col-lg-4">

            {{-- ============================================= --}}
            {{-- Pricing --}}
            {{-- ============================================= --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header">

                    <h5 class="mb-0">

                        <i class="fas fa-dollar-sign text-success me-2"></i>

                        Pricing

                    </h5>

                </div>

                <div class="card-body">

                    <div class="mb-3">

                        <label class="form-label">

                            Purchase Price

                            <span class="text-danger">*</span>

                        </label>

                        <input type="number" step="0.01" min="0" name="purchase_price"
                            class="form-control @error('purchase_price') is-invalid @enderror"
                            value="{{ old('purchase_price', $medicine->purchase_price ?? 0) }}">

                        @error('purchase_price')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>



                    <div class="mb-3">

                        <label class="form-label">

                            Selling Price

                            <span class="text-danger">*</span>

                        </label>

                        <input type="number" step="0.01" min="0" name="selling_price"
                            class="form-control @error('selling_price') is-invalid @enderror"
                            value="{{ old('selling_price', $medicine->selling_price ?? 0) }}">

                        @error('selling_price')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>



                    <div class="mb-3">

                        <label class="form-label">

                            Wholesale Price

                        </label>

                        <input type="number" step="0.01" min="0" name="wholesale_price"
                            class="form-control @error('wholesale_price') is-invalid @enderror"
                            value="{{ old('wholesale_price', $medicine->wholesale_price ?? 0) }}">

                        @error('wholesale_price')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>



                    <div class="mb-3">

                        <label class="form-label">

                            Tax %

                        </label>

                        <input type="number" step="0.01" min="0" max="100" name="tax_percentage"
                            class="form-control @error('tax_percentage') is-invalid @enderror"
                            value="{{ old('tax_percentage', $medicine->tax_percentage ?? 0) }}">

                        @error('tax_percentage')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>



                    <div>

                        <label class="form-label">

                            Status

                        </label>

                        <select name="status" class="form-select">

                            <option value="1" @selected(old('status', $medicine->status ?? 1))>
                                Active
                            </option>

                            <option value="0" @selected(old('status', $medicine->status ?? 1) == 0)>
                                Inactive
                            </option>

                        </select>

                    </div>

                </div>

            </div>

        </div>

    </div>
    <div class="row">
        <div class="col-lg-8">
            {{-- ============================================= --}}
            {{-- Additional Information --}}
            {{-- ============================================= --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header">

                    <h5 class="mb-0">

                        <i class="fas fa-image text-info me-2"></i>

                        Additional Information

                    </h5>

                </div>

                <div class="card-body">

                    {{-- Medicine Image --}}

                    <div class="mb-3">

                        <label class="form-label">

                            Medicine Image

                        </label>

                        <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp"
                            class="form-control @error('image') is-invalid @enderror">

                        @error('image')
                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>
                        @enderror

                    </div>



                    {{-- Image Preview --}}

                    @isset($medicine)

                        @if ($medicine->image)
                            <div class="text-center mb-3">

                                <img src="{{ asset('storage/' . $medicine->image) }}" class="img-fluid rounded border"
                                    style="max-height:180px;">

                            </div>
                        @endif

                    @endisset



                    {{-- SKU --}}

                    @isset($medicine)
                        <div class="mb-3">

                            <label class="form-label">

                                SKU

                            </label>

                            <input type="text" class="form-control" value="{{ $medicine->sku }}" readonly>

                        </div>
                    @endisset



                    {{-- Medicine Code --}}

                    @isset($medicine)
                        <div class="mb-3">

                            <label class="form-label">

                                Medicine Code

                            </label>

                            <input type="text" class="form-control" value="{{ $medicine->medicine_code }}" readonly>

                        </div>
                    @endisset



                    {{-- Barcode --}}

                    @isset($medicine)
                        <div class="mb-3">

                            <label class="form-label">

                                Barcode

                            </label>

                            <input type="text" class="form-control" value="{{ $medicine->barcode }}" readonly>

                        </div>
                    @endisset



                    {{-- Current Stock --}}

                    @isset($medicine)
                        <div class="mb-3">

                            <label class="form-label">

                                Current Stock

                            </label>

                            <input type="text" class="form-control"
                                value="{{ number_format($medicine->current_stock, 2) }}" readonly>

                        </div>
                    @endisset



                    {{-- Sort Order --}}

                    <div class="mb-3">

                        <label class="form-label">

                            Sort Order

                        </label>

                        <input type="number" min="0" name="sort_order"
                            class="form-control @error('sort_order') is-invalid @enderror"
                            value="{{ old('sort_order', $medicine->sort_order ?? 0) }}">

                        @error('sort_order')
                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>
                        @enderror

                    </div>

                </div>

            </div>
            
        </div>

        <div class="col-lg-4">
            {{-- ============================================= --}}
            {{-- Inventory --}}
            {{-- ============================================= --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header">

                    <h5 class="mb-0">

                        <i class="fas fa-boxes-stacked text-warning me-2"></i>

                        Inventory

                    </h5>

                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-6 mb-3">

                            <label class="form-label">

                                Opening Stock

                            </label>

                            <input type="number" step="0.01" min="0" name="opening_stock"
                                class="form-control"
                                value="{{ old('opening_stock', $medicine->opening_stock ?? 0) }}">

                        </div>



                        <div class="col-6 mb-3">

                            <label class="form-label">

                                Reorder Level

                            </label>

                            <input type="number" step="0.01" min="0" name="reorder_level"
                                class="form-control"
                                value="{{ old('reorder_level', $medicine->reorder_level ?? 0) }}">

                        </div>



                        <div class="col-6 mb-3">

                            <label class="form-label">

                                Minimum Stock

                            </label>

                            <input type="number" step="0.01" min="0" name="minimum_stock"
                                class="form-control"
                                value="{{ old('minimum_stock', $medicine->minimum_stock ?? 0) }}">

                        </div>



                        <div class="col-6 mb-3">

                            <label class="form-label">

                                Maximum Stock

                            </label>

                            <input type="number" step="0.01" min="0" name="maximum_stock"
                                class="form-control"
                                value="{{ old('maximum_stock', $medicine->maximum_stock ?? 0) }}">

                        </div>



                        <div class="col-6">

                            <label class="form-label">

                                Has Expiry

                            </label>

                            <select name="has_expiry" class="form-select">

                                <option value="1" @selected(old('has_expiry', $medicine->has_expiry ?? true))>
                                    Yes
                                </option>

                                <option value="0" @selected(old('has_expiry', $medicine->has_expiry ?? true) == 0)>
                                    No
                                </option>

                            </select>

                        </div>



                        <div class="col-6">

                            <label class="form-label">

                                Shelf Life (Months)

                            </label>

                            <input type="number" min="0" name="shelf_life_months" class="form-control"
                                value="{{ old('shelf_life_months', $medicine->shelf_life_months ?? '') }}">

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>



    {{-- ============================================= --}}
    {{-- Footer Buttons --}}
    {{-- ============================================= --}}

    <div class="card shadow-sm">

        <div class="card-body">

            <div class="d-flex justify-content-end gap-2">

                <a href="{{ route('medicines.index') }}" class="btn btn-secondary">

                    <i class="fas fa-arrow-left me-1"></i>

                    Back

                </a>



                <button type="reset" class="btn btn-warning">

                    <i class="fas fa-rotate-left me-1"></i>

                    Reset

                </button>



                <button type="submit" class="btn btn-primary">

                    <i class="fas fa-floppy-disk me-1"></i>

                    {{ isset($medicine) ? 'Update Medicine' : 'Save Medicine' }}

                </button>

            </div>

        </div>

    </div>

</form>
