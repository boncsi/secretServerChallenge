import ApiHttpClient from './client'

export const getSecret = async function () {
    let response;

    try {
        //Igy is menne, de localon ez nekem mashol van
        //response = await ApiHttpClient('/api/secret/list')
        response = await ApiHttpClient('https://firstsymfonyproject.localhost/api/secret/list')

        return response;
    } catch (err) {
        console.log(err);
    }
}
