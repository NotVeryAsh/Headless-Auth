import { NextResponse } from 'next/server'
import type { NextRequest } from 'next/server'
import {getAuthToken} from "@/lib/auth";
import {cookies} from "next/headers";

export default async function middleware(request: NextRequest) {

    const authenticated = await getAuthToken() !== undefined

    // Check if user is trying to access dashboard page whilst not logged in
    if (isPage(request, '/dashboard') && !authenticated) {
        return NextResponse.redirect(new URL('/login', request.url))
    }
}

function isPage(request: NextRequest, page: string) {
    return request.nextUrl.pathname.startsWith(page)
}