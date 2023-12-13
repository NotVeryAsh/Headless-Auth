import getToken from '@/lib/auth';

// function to make requests with method, url, data
async function sendRequest(method: string, url: string, body?: any, requireAuth: boolean = true) {

    const token = await getAuthToken();
    const headers = setHeaders(requireAuth, token);

    if(method === 'GET') {
        url += getQueryParamsFromBody(body);
        body = null;
    }

    const response = await fetch(url, {
        method,
        headers: headers,
        credentials: 'include',
        body: body && JSON.stringify(body),
    });

    return response.json()
}

function getQueryParamsFromBody(body: any)
{
    let query = '?';
    for(let key in body) {
        query += `&${key}=${body[key]}`;
    }

    return query
}

function setHeaders(requireAuth: boolean = true, token?: string)
{
    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    }

    if(requireAuth) {
        headers['Authorization'] = 'Bearer ' + token;
    }

    return headers;
}

async function getAuthToken(requireAuth: boolean = true)
{
    let token = null;

    if(requireAuth) {
        // Get new token for the user or redirect to login page
        const data = await getToken();
        token = data.token;
    }

    return token;
}