import ApiHttpClient from '../api/client'

const getSecret = async function (url) {
    let response;
    try {
        response = await ApiHttpClient(url)

        return response;
    } catch (err) {
        console.log(err);
    }
}

export default getSecret;