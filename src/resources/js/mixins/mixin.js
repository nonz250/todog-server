const debug = process.env.NODE_ENV !== 'production'

const onSuccess = (res) => {
    if (debug) {
        console.log(res)
    }
    return Promise.resolve(res)
}

const onError = (err) => {
    console.error(err)
    return err.response
}

export default {
    methods: {
        async api(method, url, params) {
            if (debug) {
                console.log('--- API RUN ---')
                console.log('method >> ' + method)
                console.log('url >> ' + url)
                console.log(params)
            }

            let config = {
                method: null,
                url: url,
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.getElementsByName('csrf-token').item(0).content
                },
                data: null,
                params: null,
            }

            if (method === 'post') {
                config.method = method
                config.data = params
            } else if (method === 'get') {
                config.method = method
                config.params = params
            } else if (method === 'put') {
                params.append('_method', 'PUT');
                config.method = 'post'
                config.data = params
            } else if (method === 'delete') {
                params.append('_method', 'DELETE');
                config.method = 'post'
                config.data = params
            } else {
                throw 'api error'
            }

            const response = await axios(config).then(onSuccess).catch(onError)

            if (debug) {
                console.log('response >> ' + JSON.stringify(response))
            }

            // if (response.status === 401) {
            //     location.reload();
            // }

            return response
        },
        getMessages(messages) {
            let results = [];
            for (let i in messages) {
                results.push(messages[i]);
            }
            return results.join("\n")
        },
        getLaravelErrorMessages(messages) {
            const errors = [];
            Object.keys(messages).forEach((value) => {
                errors.push(messages[value]);
            });
            return errors.join("\n");
        }
    }
}
