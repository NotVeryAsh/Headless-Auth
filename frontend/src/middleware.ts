import { NextResponse } from 'next/server'
import type { NextRequest } from 'next/server'
import {getAuthToken} from "@/lib/auth";
import {usePathname} from "next/navigation";

const authRoutes: string[] = [
    "calendar"
]

export default async function middleware(request: NextRequest) {

    const authenticated = await getAuthToken() !== undefined

    // Check if user is trying to access dashboard page whilst logged in
    if ((isPage(request, '/login') || isPage(request, '/register')) && authenticated) {
        return NextResponse.redirect(new URL('/calendars', request.url))
    }

    const pathName = request.nextUrl.pathname;

    // Get all url elements which are not empty strings
    let pathArray = pathName.split('/');

    // Check if user is trying to access dashboard page whilst not logged in
    if (requiresAuth(pathArray[1]) && !authenticated) {
        return NextResponse.redirect(new URL('/login', request.url))
    }
}

function isPage(request: NextRequest, page: string) {
    return request.nextUrl.pathname.startsWith(page)
}

function requiresAuth(page: string) {

    let requiresAuth = false;

    authRoutes.forEach((route) => {
       if(page.includes(route)) {
           requiresAuth = true;
           return true;
       }
    });

    return requiresAuth;
}