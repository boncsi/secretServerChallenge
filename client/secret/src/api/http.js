import axios from 'axios';

export default {
    name: 'ApiHttpClient',

    async getHttpRequest(url) {
        let response = await axios.get(url, {
            auth: {
                username: 'boncsi',
                password: 'Almafa123!'
            }
        });

        return response;
    }
}
