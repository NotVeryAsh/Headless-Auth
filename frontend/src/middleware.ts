import { NextResponse } from 'next/server'
import type { NextRequest } from 'next/server'

export function middleware(request: NextRequest) {

    const cookies = request.cookies
    const authenticated = cookies.has(process.env.SANCTUM_TOKEN_NAME)

    // Check if user is trying to access products page whilst not logged in
    // if (request.nextUrl.pathname.startsWith('/products') && !authenticated) {
    //     return NextResponse.redirect(new URL('/login', request.url))
    // }

    // Check if user is trying to access dashboard page whilst not logged in
    if (request.nextUrl.pathname.startsWith('/dashboard') && !authenticated) {
        return NextResponse.redirect(new URL('/login', request.url))
    }
}