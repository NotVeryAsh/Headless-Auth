import sendRequest from "@/lib/request";

export default async function login(formData: FormData) {

    // TODO Refactor this to work

    const credentials = {
        email: formData.get('email'),
        password: formData.get('password')
    }

    const response = sendRequest('POST', '/login', credentials, 0)

    // do the request with data
    // check for errors
    // display errors if there are any

    // if no errors
    // get token from headers
    // set token in headers
}