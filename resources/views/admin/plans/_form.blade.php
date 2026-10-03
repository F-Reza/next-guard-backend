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

    <div class="mt-4">

        <label class="form-label fw-semibold">
            Plan Features
        </label>

        <div class="text-muted small mb-3">
            Select the protection features included in this plan.
        </div>


        @php

            $currentFeatures =
                old(
                    'features',
                    $plan->features ?? []
                );

            /*
            * Support old Premium key temporarily:
            * adult_block → adult_content_block
            */

            if (
                is_array($currentFeatures)
                &&
                isset($currentFeatures['adult_block'])
                &&
                !isset(
                    $currentFeatures[
                        'adult_content_block'
                    ]
                )
            ) {

                $currentFeatures[
                    'adult_content_block'
                ] =
                    (bool)
                    $currentFeatures[
                        'adult_block'
                    ];

            }

        @endphp


        <div class="row g-3">


            {{-- Betting Block --}}
            <div class="col-md-6">

                <div class="form-check border rounded p-3">

                    <input
                        type="checkbox"
                        name="features[betting_block]"
                        id="feature_betting_block"
                        value="1"
                        class="form-check-input ms-0 me-2"
                        @checked(
                            !empty(
                                $currentFeatures[
                                    'betting_block'
                                ]
                            )
                        )
                    >

                    <label
                        for="feature_betting_block"
                        class="form-check-label fw-semibold"
                    >
                        Betting Block
                    </label>

                </div>

            </div>


            {{-- Adult Content --}}
            <div class="col-md-6">

                <div class="form-check border rounded p-3">

                    <input
                        type="checkbox"
                        name="features[adult_content_block]"
                        id="feature_adult_content_block"
                        value="1"
                        class="form-check-input ms-0 me-2"
                        @checked(
                            !empty(
                                $currentFeatures[
                                    'adult_content_block'
                                ]
                            )
                        )
                    >

                    <label
                        for="feature_adult_content_block"
                        class="form-check-label fw-semibold"
                    >
                        Adult Content Block
                    </label>

                </div>

            </div>


            {{-- Safe Search --}}
            <div class="col-md-6">

                <div class="form-check border rounded p-3">

                    <input
                        type="checkbox"
                        name="features[safe_search]"
                        id="feature_safe_search"
                        value="1"
                        class="form-check-input ms-0 me-2"
                        @checked(
                            !empty(
                                $currentFeatures[
                                    'safe_search'
                                ]
                            )
                        )
                    >

                    <label
                        for="feature_safe_search"
                        class="form-check-label fw-semibold"
                    >
                        Safe Search
                    </label>

                </div>

            </div>


            {{-- DNS --}}
            <div class="col-md-6">

                <div class="form-check border rounded p-3">

                    <input
                        type="checkbox"
                        name="features[dns_protection]"
                        id="feature_dns_protection"
                        value="1"
                        class="form-check-input ms-0 me-2"
                        @checked(
                            !empty(
                                $currentFeatures[
                                    'dns_protection'
                                ]
                            )
                        )
                    >

                    <label
                        for="feature_dns_protection"
                        class="form-check-label fw-semibold"
                    >
                        DNS Protection
                    </label>

                </div>

            </div>


            {{-- YouTube Ads --}}
            <div class="col-md-6">

                <div class="form-check border rounded p-3">

                    <input
                        type="checkbox"
                        name="features[youtube_ad_block]"
                        id="feature_youtube_ad_block"
                        value="1"
                        class="form-check-input ms-0 me-2"
                        @checked(
                            !empty(
                                $currentFeatures[
                                    'youtube_ad_block'
                                ]
                            )
                        )
                    >

                    <label
                        for="feature_youtube_ad_block"
                        class="form-check-label fw-semibold"
                    >
                        YouTube Ad Block
                    </label>

                </div>

            </div>


            {{-- Facebook Ads --}}
            <div class="col-md-6">

                <div class="form-check border rounded p-3">

                    <input
                        type="checkbox"
                        name="features[facebook_ad_block]"
                        id="feature_facebook_ad_block"
                        value="1"
                        class="form-check-input ms-0 me-2"
                        @checked(
                            !empty(
                                $currentFeatures[
                                    'facebook_ad_block'
                                ]
                            )
                        )
                    >

                    <label
                        for="feature_facebook_ad_block"
                        class="form-check-label fw-semibold"
                    >
                        Facebook Ad Block
                    </label>

                </div>

            </div>


        </div>

    </div>

    <div class="form-text">
        Enter one feature per line.
    </div>

    @error('features')

        <div class="invalid-feedback">
            {{ $message }}
        </div>

    @enderror

</div>