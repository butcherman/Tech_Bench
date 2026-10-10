import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \ArthurPatriot\Tus\Http\Controllers\TusUploadController::options
* @see vendor/arthurpatriot/laravel-tus/src/Http/Controllers/TusUploadController.php:17
* @route '/tus'
*/
export const options = (routeOptions?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: options.url(routeOptions),
    method: 'options',
})

options.definition = {
    methods: ["options"],
    url: '/tus',
} satisfies RouteDefinition<["options"]>

/**
* @see \ArthurPatriot\Tus\Http\Controllers\TusUploadController::options
* @see vendor/arthurpatriot/laravel-tus/src/Http/Controllers/TusUploadController.php:17
* @route '/tus'
*/
options.url = (routeOptions?: RouteQueryOptions) => {
    return options.definition.url
    + queryParams(routeOptions)
}

/**
* @see \ArthurPatriot\Tus\Http\Controllers\TusUploadController::options
* @see vendor/arthurpatriot/laravel-tus/src/Http/Controllers/TusUploadController.php:17
* @route '/tus'
*/
options.options = (routeOptions?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: options.url(routeOptions),
    method: 'options',
})

/**
* @see \ArthurPatriot\Tus\Http\Controllers\TusUploadController::post
* @see vendor/arthurpatriot/laravel-tus/src/Http/Controllers/TusUploadController.php:25
* @route '/tus'
*/
export const post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: post.url(options),
    method: 'post',
})

post.definition = {
    methods: ["post"],
    url: '/tus',
} satisfies RouteDefinition<["post"]>

/**
* @see \ArthurPatriot\Tus\Http\Controllers\TusUploadController::post
* @see vendor/arthurpatriot/laravel-tus/src/Http/Controllers/TusUploadController.php:25
* @route '/tus'
*/
post.url = (options?: RouteQueryOptions) => {
    return post.definition.url + queryParams(options)
}

/**
* @see \ArthurPatriot\Tus\Http\Controllers\TusUploadController::post
* @see vendor/arthurpatriot/laravel-tus/src/Http/Controllers/TusUploadController.php:25
* @route '/tus'
*/
post.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: post.url(options),
    method: 'post',
})

/**
* @see \ArthurPatriot\Tus\Http\Controllers\TusUploadController::head
* @see vendor/arthurpatriot/laravel-tus/src/Http/Controllers/TusUploadController.php:53
* @route '/tus/{id}'
*/
export const head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: head.url(args, options),
    method: 'head',
})

head.definition = {
    methods: ["head"],
    url: '/tus/{id}',
} satisfies RouteDefinition<["head"]>

/**
* @see \ArthurPatriot\Tus\Http\Controllers\TusUploadController::head
* @see vendor/arthurpatriot/laravel-tus/src/Http/Controllers/TusUploadController.php:53
* @route '/tus/{id}'
*/
head.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { id: args }
    }

    if (Array.isArray(args)) {
        args = {
            id: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        id: args.id,
    }

    return head.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \ArthurPatriot\Tus\Http\Controllers\TusUploadController::head
* @see vendor/arthurpatriot/laravel-tus/src/Http/Controllers/TusUploadController.php:53
* @route '/tus/{id}'
*/
head.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: head.url(args, options),
    method: 'head',
})

/**
* @see \ArthurPatriot\Tus\Http\Controllers\TusUploadController::patch
* @see vendor/arthurpatriot/laravel-tus/src/Http/Controllers/TusUploadController.php:63
* @route '/tus/{id}'
*/
export const patch = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: patch.url(args, options),
    method: 'patch',
})

patch.definition = {
    methods: ["patch"],
    url: '/tus/{id}',
} satisfies RouteDefinition<["patch"]>

/**
* @see \ArthurPatriot\Tus\Http\Controllers\TusUploadController::patch
* @see vendor/arthurpatriot/laravel-tus/src/Http/Controllers/TusUploadController.php:63
* @route '/tus/{id}'
*/
patch.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { id: args }
    }

    if (Array.isArray(args)) {
        args = {
            id: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        id: args.id,
    }

    return patch.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \ArthurPatriot\Tus\Http\Controllers\TusUploadController::patch
* @see vendor/arthurpatriot/laravel-tus/src/Http/Controllers/TusUploadController.php:63
* @route '/tus/{id}'
*/
patch.patch = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: patch.url(args, options),
    method: 'patch',
})

/**
* @see \ArthurPatriot\Tus\Http\Controllers\TusUploadController::deleteMethod
* @see vendor/arthurpatriot/laravel-tus/src/Http/Controllers/TusUploadController.php:107
* @route '/tus/{id}'
*/
export const deleteMethod = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: deleteMethod.url(args, options),
    method: 'delete',
})

deleteMethod.definition = {
    methods: ["delete"],
    url: '/tus/{id}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \ArthurPatriot\Tus\Http\Controllers\TusUploadController::deleteMethod
* @see vendor/arthurpatriot/laravel-tus/src/Http/Controllers/TusUploadController.php:107
* @route '/tus/{id}'
*/
deleteMethod.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { id: args }
    }

    if (Array.isArray(args)) {
        args = {
            id: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        id: args.id,
    }

    return deleteMethod.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \ArthurPatriot\Tus\Http\Controllers\TusUploadController::deleteMethod
* @see vendor/arthurpatriot/laravel-tus/src/Http/Controllers/TusUploadController.php:107
* @route '/tus/{id}'
*/
deleteMethod.delete = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: deleteMethod.url(args, options),
    method: 'delete',
})

const tus = {
    options: Object.assign(options, options),
    post: Object.assign(post, post),
    head: Object.assign(head, head),
    patch: Object.assign(patch, patch),
    delete: Object.assign(deleteMethod, deleteMethod),
}

export default tus