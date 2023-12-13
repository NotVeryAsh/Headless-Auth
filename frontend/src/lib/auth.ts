import Cookies from "universal-cookie";
import {redirect} from "next/navigation";
import moment from "moment";

export default async function getToken() {
    const cookies = new Cookies();
    const token = cookies.get(process.env.SANCTUM_TOKEN_NAME);

    // User is not logged in
    if (token) {
        redirect('login')
    }

    const response = await fetch('api/auth/get-token')

    const status = response.status

    // User is not logged in
    if (status === 401) {
        redirect('login')
    }

    // Get data from response
    const data = await response.json();

    setTokenCookie(data.token)

    return data;
}

export function setTokenCookie(token: string)
{
    // Store token in a cookie
    const cookies = new Cookies();
    cookies.set(process.env.SANCTUM_TOKEN_NAME, token, {
        expires: moment().add("1", "week").toDate(),
    });
}