'use server'

import {cookies} from "next/headers";
import { redirect } from 'next/navigation'

export default async function storeAuthToken(token: string) {
    cookies().set({
        name: process.env.NEXT_PUBLIC_SANCTUM_TOKEN_NAME,
        value: token,
        httpOnly: true,
        path: '/',
        expires: Date.now() + parseInt(process.env.NEXT_PUBLIC_SANCTUM_TOKEN_EXPIRATION, 10),
    })
}

export async function getAuthToken() {
    return cookies().get(process.env.NEXT_PUBLIC_SANCTUM_TOKEN_NAME);
}

export async function redirectTo(redirectString: string) {
    redirect(redirectString)
}