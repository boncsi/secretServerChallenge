import axios from 'axios'

const ApiHttpClient = axios.create({
    baseURL : document.baseURI,
    headers : {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    },
    auth : {
        username:"boncsi",
        password: "Almafa123!"
    }
})

export default ApiHttpClient;
