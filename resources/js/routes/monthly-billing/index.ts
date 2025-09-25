import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\FacturacionMensualController::list
* @see app/Http/Controllers/FacturacionMensualController.php:49
* @route '/monthly-billing/list'
*/
export const list = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: list.url(options),
    method: 'get',
})

list.definition = {
    methods: ["get","head"],
    url: '/monthly-billing/list',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\FacturacionMensualController::list
* @see app/Http/Controllers/FacturacionMensualController.php:49
* @route '/monthly-billing/list'
*/
list.url = (options?: RouteQueryOptions) => {
    return list.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\FacturacionMensualController::list
* @see app/Http/Controllers/FacturacionMensualController.php:49
* @route '/monthly-billing/list'
*/
list.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: list.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\FacturacionMensualController::list
* @see app/Http/Controllers/FacturacionMensualController.php:49
* @route '/monthly-billing/list'
*/
list.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: list.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\FacturacionMensualController::list
* @see app/Http/Controllers/FacturacionMensualController.php:49
* @route '/monthly-billing/list'
*/
const listForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: list.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\FacturacionMensualController::list
* @see app/Http/Controllers/FacturacionMensualController.php:49
* @route '/monthly-billing/list'
*/
listForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: list.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\FacturacionMensualController::list
* @see app/Http/Controllers/FacturacionMensualController.php:49
* @route '/monthly-billing/list'
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
* @see \App\Http\Controllers\FacturacionMensualController::index
* @see app/Http/Controllers/FacturacionMensualController.php:18
* @route '/monthly-billing'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/monthly-billing',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\FacturacionMensualController::index
* @see app/Http/Controllers/FacturacionMensualController.php:18
* @route '/monthly-billing'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\FacturacionMensualController::index
* @see app/Http/Controllers/FacturacionMensualController.php:18
* @route '/monthly-billing'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\FacturacionMensualController::index
* @see app/Http/Controllers/FacturacionMensualController.php:18
* @route '/monthly-billing'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\FacturacionMensualController::index
* @see app/Http/Controllers/FacturacionMensualController.php:18
* @route '/monthly-billing'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\FacturacionMensualController::index
* @see app/Http/Controllers/FacturacionMensualController.php:18
* @route '/monthly-billing'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\FacturacionMensualController::index
* @see app/Http/Controllers/FacturacionMensualController.php:18
* @route '/monthly-billing'
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
* @see \App\Http\Controllers\FacturacionMensualController::create
* @see app/Http/Controllers/FacturacionMensualController.php:0
* @route '/monthly-billing/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/monthly-billing/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\FacturacionMensualController::create
* @see app/Http/Controllers/FacturacionMensualController.php:0
* @route '/monthly-billing/create'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\FacturacionMensualController::create
* @see app/Http/Controllers/FacturacionMensualController.php:0
* @route '/monthly-billing/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\FacturacionMensualController::create
* @see app/Http/Controllers/FacturacionMensualController.php:0
* @route '/monthly-billing/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\FacturacionMensualController::create
* @see app/Http/Controllers/FacturacionMensualController.php:0
* @route '/monthly-billing/create'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\FacturacionMensualController::create
* @see app/Http/Controllers/FacturacionMensualController.php:0
* @route '/monthly-billing/create'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\FacturacionMensualController::create
* @see app/Http/Controllers/FacturacionMensualController.php:0
* @route '/monthly-billing/create'
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
* @see \App\Http\Controllers\FacturacionMensualController::store
* @see app/Http/Controllers/FacturacionMensualController.php:126
* @route '/monthly-billing'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/monthly-billing',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\FacturacionMensualController::store
* @see app/Http/Controllers/FacturacionMensualController.php:126
* @route '/monthly-billing'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\FacturacionMensualController::store
* @see app/Http/Controllers/FacturacionMensualController.php:126
* @route '/monthly-billing'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\FacturacionMensualController::store
* @see app/Http/Controllers/FacturacionMensualController.php:126
* @route '/monthly-billing'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\FacturacionMensualController::store
* @see app/Http/Controllers/FacturacionMensualController.php:126
* @route '/monthly-billing'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\FacturacionMensualController::show
* @see app/Http/Controllers/FacturacionMensualController.php:191
* @route '/monthly-billing/{monthly_billing}'
*/
export const show = (args: { monthly_billing: number | { id: number } } | [monthly_billing: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/monthly-billing/{monthly_billing}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\FacturacionMensualController::show
* @see app/Http/Controllers/FacturacionMensualController.php:191
* @route '/monthly-billing/{monthly_billing}'
*/
show.url = (args: { monthly_billing: number | { id: number } } | [monthly_billing: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { monthly_billing: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { monthly_billing: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            monthly_billing: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        monthly_billing: typeof args.monthly_billing === 'object'
        ? args.monthly_billing.id
        : args.monthly_billing,
    }

    return show.definition.url
            .replace('{monthly_billing}', parsedArgs.monthly_billing.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\FacturacionMensualController::show
* @see app/Http/Controllers/FacturacionMensualController.php:191
* @route '/monthly-billing/{monthly_billing}'
*/
show.get = (args: { monthly_billing: number | { id: number } } | [monthly_billing: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\FacturacionMensualController::show
* @see app/Http/Controllers/FacturacionMensualController.php:191
* @route '/monthly-billing/{monthly_billing}'
*/
show.head = (args: { monthly_billing: number | { id: number } } | [monthly_billing: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\FacturacionMensualController::show
* @see app/Http/Controllers/FacturacionMensualController.php:191
* @route '/monthly-billing/{monthly_billing}'
*/
const showForm = (args: { monthly_billing: number | { id: number } } | [monthly_billing: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\FacturacionMensualController::show
* @see app/Http/Controllers/FacturacionMensualController.php:191
* @route '/monthly-billing/{monthly_billing}'
*/
showForm.get = (args: { monthly_billing: number | { id: number } } | [monthly_billing: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\FacturacionMensualController::show
* @see app/Http/Controllers/FacturacionMensualController.php:191
* @route '/monthly-billing/{monthly_billing}'
*/
showForm.head = (args: { monthly_billing: number | { id: number } } | [monthly_billing: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\FacturacionMensualController::edit
* @see app/Http/Controllers/FacturacionMensualController.php:201
* @route '/monthly-billing/{monthly_billing}/edit'
*/
export const edit = (args: { monthly_billing: number | { id: number } } | [monthly_billing: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/monthly-billing/{monthly_billing}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\FacturacionMensualController::edit
* @see app/Http/Controllers/FacturacionMensualController.php:201
* @route '/monthly-billing/{monthly_billing}/edit'
*/
edit.url = (args: { monthly_billing: number | { id: number } } | [monthly_billing: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { monthly_billing: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { monthly_billing: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            monthly_billing: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        monthly_billing: typeof args.monthly_billing === 'object'
        ? args.monthly_billing.id
        : args.monthly_billing,
    }

    return edit.definition.url
            .replace('{monthly_billing}', parsedArgs.monthly_billing.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\FacturacionMensualController::edit
* @see app/Http/Controllers/FacturacionMensualController.php:201
* @route '/monthly-billing/{monthly_billing}/edit'
*/
edit.get = (args: { monthly_billing: number | { id: number } } | [monthly_billing: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\FacturacionMensualController::edit
* @see app/Http/Controllers/FacturacionMensualController.php:201
* @route '/monthly-billing/{monthly_billing}/edit'
*/
edit.head = (args: { monthly_billing: number | { id: number } } | [monthly_billing: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\FacturacionMensualController::edit
* @see app/Http/Controllers/FacturacionMensualController.php:201
* @route '/monthly-billing/{monthly_billing}/edit'
*/
const editForm = (args: { monthly_billing: number | { id: number } } | [monthly_billing: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\FacturacionMensualController::edit
* @see app/Http/Controllers/FacturacionMensualController.php:201
* @route '/monthly-billing/{monthly_billing}/edit'
*/
editForm.get = (args: { monthly_billing: number | { id: number } } | [monthly_billing: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\FacturacionMensualController::edit
* @see app/Http/Controllers/FacturacionMensualController.php:201
* @route '/monthly-billing/{monthly_billing}/edit'
*/
editForm.head = (args: { monthly_billing: number | { id: number } } | [monthly_billing: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\FacturacionMensualController::update
* @see app/Http/Controllers/FacturacionMensualController.php:211
* @route '/monthly-billing/{monthly_billing}'
*/
export const update = (args: { monthly_billing: number | { id: number } } | [monthly_billing: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/monthly-billing/{monthly_billing}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\FacturacionMensualController::update
* @see app/Http/Controllers/FacturacionMensualController.php:211
* @route '/monthly-billing/{monthly_billing}'
*/
update.url = (args: { monthly_billing: number | { id: number } } | [monthly_billing: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { monthly_billing: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { monthly_billing: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            monthly_billing: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        monthly_billing: typeof args.monthly_billing === 'object'
        ? args.monthly_billing.id
        : args.monthly_billing,
    }

    return update.definition.url
            .replace('{monthly_billing}', parsedArgs.monthly_billing.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\FacturacionMensualController::update
* @see app/Http/Controllers/FacturacionMensualController.php:211
* @route '/monthly-billing/{monthly_billing}'
*/
update.put = (args: { monthly_billing: number | { id: number } } | [monthly_billing: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\FacturacionMensualController::update
* @see app/Http/Controllers/FacturacionMensualController.php:211
* @route '/monthly-billing/{monthly_billing}'
*/
update.patch = (args: { monthly_billing: number | { id: number } } | [monthly_billing: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\FacturacionMensualController::update
* @see app/Http/Controllers/FacturacionMensualController.php:211
* @route '/monthly-billing/{monthly_billing}'
*/
const updateForm = (args: { monthly_billing: number | { id: number } } | [monthly_billing: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\FacturacionMensualController::update
* @see app/Http/Controllers/FacturacionMensualController.php:211
* @route '/monthly-billing/{monthly_billing}'
*/
updateForm.put = (args: { monthly_billing: number | { id: number } } | [monthly_billing: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\FacturacionMensualController::update
* @see app/Http/Controllers/FacturacionMensualController.php:211
* @route '/monthly-billing/{monthly_billing}'
*/
updateForm.patch = (args: { monthly_billing: number | { id: number } } | [monthly_billing: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\FacturacionMensualController::destroy
* @see app/Http/Controllers/FacturacionMensualController.php:271
* @route '/monthly-billing/{monthly_billing}'
*/
export const destroy = (args: { monthly_billing: number | { id: number } } | [monthly_billing: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/monthly-billing/{monthly_billing}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\FacturacionMensualController::destroy
* @see app/Http/Controllers/FacturacionMensualController.php:271
* @route '/monthly-billing/{monthly_billing}'
*/
destroy.url = (args: { monthly_billing: number | { id: number } } | [monthly_billing: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { monthly_billing: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { monthly_billing: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            monthly_billing: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        monthly_billing: typeof args.monthly_billing === 'object'
        ? args.monthly_billing.id
        : args.monthly_billing,
    }

    return destroy.definition.url
            .replace('{monthly_billing}', parsedArgs.monthly_billing.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\FacturacionMensualController::destroy
* @see app/Http/Controllers/FacturacionMensualController.php:271
* @route '/monthly-billing/{monthly_billing}'
*/
destroy.delete = (args: { monthly_billing: number | { id: number } } | [monthly_billing: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\FacturacionMensualController::destroy
* @see app/Http/Controllers/FacturacionMensualController.php:271
* @route '/monthly-billing/{monthly_billing}'
*/
const destroyForm = (args: { monthly_billing: number | { id: number } } | [monthly_billing: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\FacturacionMensualController::destroy
* @see app/Http/Controllers/FacturacionMensualController.php:271
* @route '/monthly-billing/{monthly_billing}'
*/
destroyForm.delete = (args: { monthly_billing: number | { id: number } } | [monthly_billing: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const monthlyBilling = {
    list: Object.assign(list, list),
    index: Object.assign(index, index),
    create: Object.assign(create, create),
    store: Object.assign(store, store),
    show: Object.assign(show, show),
    edit: Object.assign(edit, edit),
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
}

export default monthlyBilling