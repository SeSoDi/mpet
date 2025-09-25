import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\VentaMensualController::list
* @see app/Http/Controllers/VentaMensualController.php:39
* @route '/monthly-sales/list'
*/
export const list = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: list.url(options),
    method: 'get',
})

list.definition = {
    methods: ["get","head"],
    url: '/monthly-sales/list',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\VentaMensualController::list
* @see app/Http/Controllers/VentaMensualController.php:39
* @route '/monthly-sales/list'
*/
list.url = (options?: RouteQueryOptions) => {
    return list.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\VentaMensualController::list
* @see app/Http/Controllers/VentaMensualController.php:39
* @route '/monthly-sales/list'
*/
list.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: list.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\VentaMensualController::list
* @see app/Http/Controllers/VentaMensualController.php:39
* @route '/monthly-sales/list'
*/
list.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: list.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\VentaMensualController::list
* @see app/Http/Controllers/VentaMensualController.php:39
* @route '/monthly-sales/list'
*/
const listForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: list.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\VentaMensualController::list
* @see app/Http/Controllers/VentaMensualController.php:39
* @route '/monthly-sales/list'
*/
listForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: list.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\VentaMensualController::list
* @see app/Http/Controllers/VentaMensualController.php:39
* @route '/monthly-sales/list'
*/
listForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: list.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

list.form = listForm

/**
* @see \App\Http\Controllers\VentaMensualController::index
* @see app/Http/Controllers/VentaMensualController.php:17
* @route '/monthly-sales'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/monthly-sales',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\VentaMensualController::index
* @see app/Http/Controllers/VentaMensualController.php:17
* @route '/monthly-sales'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\VentaMensualController::index
* @see app/Http/Controllers/VentaMensualController.php:17
* @route '/monthly-sales'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\VentaMensualController::index
* @see app/Http/Controllers/VentaMensualController.php:17
* @route '/monthly-sales'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\VentaMensualController::index
* @see app/Http/Controllers/VentaMensualController.php:17
* @route '/monthly-sales'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\VentaMensualController::index
* @see app/Http/Controllers/VentaMensualController.php:17
* @route '/monthly-sales'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\VentaMensualController::index
* @see app/Http/Controllers/VentaMensualController.php:17
* @route '/monthly-sales'
*/
indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

/**
* @see \App\Http\Controllers\VentaMensualController::create
* @see app/Http/Controllers/VentaMensualController.php:101
* @route '/monthly-sales/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/monthly-sales/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\VentaMensualController::create
* @see app/Http/Controllers/VentaMensualController.php:101
* @route '/monthly-sales/create'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\VentaMensualController::create
* @see app/Http/Controllers/VentaMensualController.php:101
* @route '/monthly-sales/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\VentaMensualController::create
* @see app/Http/Controllers/VentaMensualController.php:101
* @route '/monthly-sales/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\VentaMensualController::create
* @see app/Http/Controllers/VentaMensualController.php:101
* @route '/monthly-sales/create'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\VentaMensualController::create
* @see app/Http/Controllers/VentaMensualController.php:101
* @route '/monthly-sales/create'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\VentaMensualController::create
* @see app/Http/Controllers/VentaMensualController.php:101
* @route '/monthly-sales/create'
*/
createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

create.form = createForm

/**
* @see \App\Http\Controllers\VentaMensualController::store
* @see app/Http/Controllers/VentaMensualController.php:109
* @route '/monthly-sales'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/monthly-sales',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\VentaMensualController::store
* @see app/Http/Controllers/VentaMensualController.php:109
* @route '/monthly-sales'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\VentaMensualController::store
* @see app/Http/Controllers/VentaMensualController.php:109
* @route '/monthly-sales'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\VentaMensualController::store
* @see app/Http/Controllers/VentaMensualController.php:109
* @route '/monthly-sales'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\VentaMensualController::store
* @see app/Http/Controllers/VentaMensualController.php:109
* @route '/monthly-sales'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\VentaMensualController::show
* @see app/Http/Controllers/VentaMensualController.php:169
* @route '/monthly-sales/{monthly_sale}'
*/
export const show = (args: { monthly_sale: number | { id: number } } | [monthly_sale: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/monthly-sales/{monthly_sale}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\VentaMensualController::show
* @see app/Http/Controllers/VentaMensualController.php:169
* @route '/monthly-sales/{monthly_sale}'
*/
show.url = (args: { monthly_sale: number | { id: number } } | [monthly_sale: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { monthly_sale: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { monthly_sale: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            monthly_sale: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        monthly_sale: typeof args.monthly_sale === 'object'
        ? args.monthly_sale.id
        : args.monthly_sale,
    }

    return show.definition.url
            .replace('{monthly_sale}', parsedArgs.monthly_sale.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\VentaMensualController::show
* @see app/Http/Controllers/VentaMensualController.php:169
* @route '/monthly-sales/{monthly_sale}'
*/
show.get = (args: { monthly_sale: number | { id: number } } | [monthly_sale: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\VentaMensualController::show
* @see app/Http/Controllers/VentaMensualController.php:169
* @route '/monthly-sales/{monthly_sale}'
*/
show.head = (args: { monthly_sale: number | { id: number } } | [monthly_sale: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\VentaMensualController::show
* @see app/Http/Controllers/VentaMensualController.php:169
* @route '/monthly-sales/{monthly_sale}'
*/
const showForm = (args: { monthly_sale: number | { id: number } } | [monthly_sale: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\VentaMensualController::show
* @see app/Http/Controllers/VentaMensualController.php:169
* @route '/monthly-sales/{monthly_sale}'
*/
showForm.get = (args: { monthly_sale: number | { id: number } } | [monthly_sale: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\VentaMensualController::show
* @see app/Http/Controllers/VentaMensualController.php:169
* @route '/monthly-sales/{monthly_sale}'
*/
showForm.head = (args: { monthly_sale: number | { id: number } } | [monthly_sale: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

/**
* @see \App\Http\Controllers\VentaMensualController::edit
* @see app/Http/Controllers/VentaMensualController.php:179
* @route '/monthly-sales/{monthly_sale}/edit'
*/
export const edit = (args: { monthly_sale: number | { id: number } } | [monthly_sale: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/monthly-sales/{monthly_sale}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\VentaMensualController::edit
* @see app/Http/Controllers/VentaMensualController.php:179
* @route '/monthly-sales/{monthly_sale}/edit'
*/
edit.url = (args: { monthly_sale: number | { id: number } } | [monthly_sale: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { monthly_sale: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { monthly_sale: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            monthly_sale: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        monthly_sale: typeof args.monthly_sale === 'object'
        ? args.monthly_sale.id
        : args.monthly_sale,
    }

    return edit.definition.url
            .replace('{monthly_sale}', parsedArgs.monthly_sale.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\VentaMensualController::edit
* @see app/Http/Controllers/VentaMensualController.php:179
* @route '/monthly-sales/{monthly_sale}/edit'
*/
edit.get = (args: { monthly_sale: number | { id: number } } | [monthly_sale: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\VentaMensualController::edit
* @see app/Http/Controllers/VentaMensualController.php:179
* @route '/monthly-sales/{monthly_sale}/edit'
*/
edit.head = (args: { monthly_sale: number | { id: number } } | [monthly_sale: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\VentaMensualController::edit
* @see app/Http/Controllers/VentaMensualController.php:179
* @route '/monthly-sales/{monthly_sale}/edit'
*/
const editForm = (args: { monthly_sale: number | { id: number } } | [monthly_sale: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\VentaMensualController::edit
* @see app/Http/Controllers/VentaMensualController.php:179
* @route '/monthly-sales/{monthly_sale}/edit'
*/
editForm.get = (args: { monthly_sale: number | { id: number } } | [monthly_sale: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\VentaMensualController::edit
* @see app/Http/Controllers/VentaMensualController.php:179
* @route '/monthly-sales/{monthly_sale}/edit'
*/
editForm.head = (args: { monthly_sale: number | { id: number } } | [monthly_sale: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

edit.form = editForm

/**
* @see \App\Http\Controllers\VentaMensualController::update
* @see app/Http/Controllers/VentaMensualController.php:189
* @route '/monthly-sales/{monthly_sale}'
*/
export const update = (args: { monthly_sale: number | { id: number } } | [monthly_sale: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/monthly-sales/{monthly_sale}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\VentaMensualController::update
* @see app/Http/Controllers/VentaMensualController.php:189
* @route '/monthly-sales/{monthly_sale}'
*/
update.url = (args: { monthly_sale: number | { id: number } } | [monthly_sale: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { monthly_sale: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { monthly_sale: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            monthly_sale: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        monthly_sale: typeof args.monthly_sale === 'object'
        ? args.monthly_sale.id
        : args.monthly_sale,
    }

    return update.definition.url
            .replace('{monthly_sale}', parsedArgs.monthly_sale.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\VentaMensualController::update
* @see app/Http/Controllers/VentaMensualController.php:189
* @route '/monthly-sales/{monthly_sale}'
*/
update.put = (args: { monthly_sale: number | { id: number } } | [monthly_sale: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\VentaMensualController::update
* @see app/Http/Controllers/VentaMensualController.php:189
* @route '/monthly-sales/{monthly_sale}'
*/
update.patch = (args: { monthly_sale: number | { id: number } } | [monthly_sale: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\VentaMensualController::update
* @see app/Http/Controllers/VentaMensualController.php:189
* @route '/monthly-sales/{monthly_sale}'
*/
const updateForm = (args: { monthly_sale: number | { id: number } } | [monthly_sale: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\VentaMensualController::update
* @see app/Http/Controllers/VentaMensualController.php:189
* @route '/monthly-sales/{monthly_sale}'
*/
updateForm.put = (args: { monthly_sale: number | { id: number } } | [monthly_sale: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\VentaMensualController::update
* @see app/Http/Controllers/VentaMensualController.php:189
* @route '/monthly-sales/{monthly_sale}'
*/
updateForm.patch = (args: { monthly_sale: number | { id: number } } | [monthly_sale: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

update.form = updateForm

/**
* @see \App\Http\Controllers\VentaMensualController::destroy
* @see app/Http/Controllers/VentaMensualController.php:259
* @route '/monthly-sales/{monthly_sale}'
*/
export const destroy = (args: { monthly_sale: number | { id: number } } | [monthly_sale: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/monthly-sales/{monthly_sale}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\VentaMensualController::destroy
* @see app/Http/Controllers/VentaMensualController.php:259
* @route '/monthly-sales/{monthly_sale}'
*/
destroy.url = (args: { monthly_sale: number | { id: number } } | [monthly_sale: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { monthly_sale: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { monthly_sale: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            monthly_sale: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        monthly_sale: typeof args.monthly_sale === 'object'
        ? args.monthly_sale.id
        : args.monthly_sale,
    }

    return destroy.definition.url
            .replace('{monthly_sale}', parsedArgs.monthly_sale.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\VentaMensualController::destroy
* @see app/Http/Controllers/VentaMensualController.php:259
* @route '/monthly-sales/{monthly_sale}'
*/
destroy.delete = (args: { monthly_sale: number | { id: number } } | [monthly_sale: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\VentaMensualController::destroy
* @see app/Http/Controllers/VentaMensualController.php:259
* @route '/monthly-sales/{monthly_sale}'
*/
const destroyForm = (args: { monthly_sale: number | { id: number } } | [monthly_sale: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\VentaMensualController::destroy
* @see app/Http/Controllers/VentaMensualController.php:259
* @route '/monthly-sales/{monthly_sale}'
*/
destroyForm.delete = (args: { monthly_sale: number | { id: number } } | [monthly_sale: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

/**
* @see \App\Http\Controllers\VentaMensualController::capture
* @see app/Http/Controllers/VentaMensualController.php:79
* @route '/monthly-sales-capture'
*/
export const capture = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: capture.url(options),
    method: 'get',
})

capture.definition = {
    methods: ["get","head"],
    url: '/monthly-sales-capture',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\VentaMensualController::capture
* @see app/Http/Controllers/VentaMensualController.php:79
* @route '/monthly-sales-capture'
*/
capture.url = (options?: RouteQueryOptions) => {
    return capture.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\VentaMensualController::capture
* @see app/Http/Controllers/VentaMensualController.php:79
* @route '/monthly-sales-capture'
*/
capture.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: capture.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\VentaMensualController::capture
* @see app/Http/Controllers/VentaMensualController.php:79
* @route '/monthly-sales-capture'
*/
capture.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: capture.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\VentaMensualController::capture
* @see app/Http/Controllers/VentaMensualController.php:79
* @route '/monthly-sales-capture'
*/
const captureForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: capture.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\VentaMensualController::capture
* @see app/Http/Controllers/VentaMensualController.php:79
* @route '/monthly-sales-capture'
*/
captureForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: capture.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\VentaMensualController::capture
* @see app/Http/Controllers/VentaMensualController.php:79
* @route '/monthly-sales-capture'
*/
captureForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: capture.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

capture.form = captureForm

const VentaMensualController = { list, index, create, store, show, edit, update, destroy, capture }

export default VentaMensualController