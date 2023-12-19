export default async function sendRequest(method: string, url: string, body?: any, cacheTime: number = 3600) {

    url = `${process.env.LARAVEL_BACKEND_API}${url}`

    const response = await fetch(url, {
        method,
        headers: [
            ['Accept', 'application/json'],
            ['Content-Type', 'application/json'],
        ],
        body: body && JSON.stringify(body),
        next: {
            revalidate: cacheTime
        }
    });

    return response.json()
}