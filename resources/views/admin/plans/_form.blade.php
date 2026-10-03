<div class="mb-3">

    <label
        for="name"
        class="form-label"
    >
        Plan Name
    </label>

    <input
        type="text"
        name="name"
        id="name"
        value="{{ old(
            'name',
            $plan->name ?? ''
        ) }}"
        class="form-control
            @error('name')
            is-invalid
            @enderror"
        maxlength="100"
        required
    >

    @error('name')

        <div class="invalid-feedback">
            {{ $message }}
        </div>

    @enderror

</div>


<div class="mb-3">

    <label
        for="description"
        class="form-label"
    >
        Description
    </label>

    <textarea
        name="description"
        id="description"
        rows="3"
        class="form-control
            @error('description')
            is-invalid
            @enderror"
        maxlength="1000"
    >{{ old(
        'description',
        $plan->description ?? ''
    ) }}</textarea>

    @error('description')

        <div class="invalid-feedback">
            {{ $message }}
        </div>

    @enderror

</div>


<div class="row g-3">

    <div class="col-md-4">

        <label
            for="price"
            class="form-label"
        >
            Price
        </label>

        <input
            type="number"
            name="price"
            id="price"
            step="0.01"
            min="0"
            value="{{ old(
                'price',
                $plan->price ?? ''
            ) }}"
            class="form-control
                @error('price')
                is-invalid
                @enderror"
            required
        >

        @error('price')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    <div class="col-md-4">

        <label
            for="currency"
            class="form-label"
        >
            Currency
        </label>

        <input
            type="text"
            name="currency"
            id="currency"
            maxlength="10"
            value="{{ old(
                'currency',
                $plan->currency ?? 'BDT'
            ) }}"
            class="form-control
                @error('currency')
                is-invalid
                @enderror"
            required
        >

        @error('currency')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    <div class="col-md-4">

        <label
            for="duration_days"
            class="form-label"
        >
            Duration
        </label>

        <div class="input-group">

            <input
                type="number"
                name="duration_days"
                id="duration_days"
                min="1"
                max="3650"
                value="{{ old(
                    'duration_days',
                    $plan->duration_days ?? ''
                ) }}"
                class="form-control
                    @error('duration_days')
                    is-invalid
                    @enderror"
                required
            >

            <span class="input-group-text">
                Days
            </span>

        </div>

        @error('duration_days')

            <div class="text-danger small mt-1">
                {{ $message }}
            </div>

        @enderror

    </div>

</div>


<div class="row g-3 mt-1">

    <div class="col-md-6">

        <label
            for="device_limit"
            class="form-label"
        >
            Device Limit
        </label>

        <input
            type="number"
            name="device_limit"
            id="device_limit"
            min="1"
            max="100"
            value="{{ old(
                'device_limit',
                $plan->device_limit ?? 1
            ) }}"
            class="form-control
                @error('device_limit')
                is-invalid
                @enderror"
            required
        >

        @error('device_limit')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    <div class="col-md-6">

        <label
            for="status"
            class="form-label"
        >
            Status
        </label>

        <select
            name="status"
            id="status"
            class="form-select
                @error('status')
                is-invalid
                @enderror"
            required
        >

            <option
                value="active"
                @selected(
                    old(
                        'status',
                        $plan->status ?? 'active'
                    ) === 'active'
                )
            >
                Active
            </option>

            <option
                value="inactive"
                @selected(
                    old(
                        'status',
                        $plan->status ?? 'active'
                    ) === 'inactive'
                )
            >
                Inactive
            </option>

        </select>

        @error('status')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>

</div>


<div class="mb-3 mt-3">

    <label
        for="features"
        class="form-label"
    >
        Features
    </label>

    <textarea
        name="features"
        id="features"
        rows="7"
        class="form-control
            @error('features')
            is-invalid
            @enderror"
        placeholder="One feature per line"
    >{{ old(
        'features',
        isset($plan)
            ? implode(
                "\n",
                $plan->features ?? []
            )
            : ''
    ) }}</textarea>

    <div class="form-text">
        Enter one feature per line.
    </div>

    @error('features')

        <div class="invalid-feedback">
            {{ $message }}
        </div>

    @enderror

</div>