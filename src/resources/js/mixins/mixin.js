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
            let config = {}
            if (method === 'post') {
                config = {
                    method: method,
                    url: url,
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    data: params
                }
            } else if (method === 'get') {
                config = {
                    method: method,
                    url: url,
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    params: params
                }
            } else {
                throw 'api error'
            }
            const response = await axios(config).then(onSuccess).catch(onError)
            if (debug) {
                console.log('response >> ' + JSON.stringify(response))
            }
            return response
        },
        getMessages(messages) {
            let results = [];
            for (let i in messages) {
                results.push(messages[i]);
            }
            return results.join("\n")
        }
    }
}
