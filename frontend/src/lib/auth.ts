import sendRequest from "@/lib/request";

export default async function login(formData: FormData) {

    // TODO Refactor this to work

    const credentials = {
        email: formData.get('email'),
        password: formData.get('password')
    }

    sendRequest('POST', '/login', credentials, 0)

}