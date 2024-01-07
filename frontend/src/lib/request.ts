export default async function sendRequest(method: string, url: string, body?: any, cacheTime: number = 3600) {

    url = `${process.env.NEXT_PUBLIC_LARAVEL_BACKEND_API}${url}`

    return await fetch(url, {
        method,
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: body && JSON.stringify(body),
        next: {
            revalidate: cacheTime
        },
        credentials: 'include'
    });
}