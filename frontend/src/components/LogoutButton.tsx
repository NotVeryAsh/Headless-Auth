"use client"

import React, {useState} from "react";
import PrimaryButton from "@/components/PrimaryButton";
import { usePathname } from 'next/navigation'
import {deleteAuthToken, getAuthToken, redirectTo} from "@/lib/auth";
import sendRequest from "@/lib/request";

async function handleClick(event: any, isSubmitting: any, setIsSubmitting: any) {

    // Don't proceed if user is currently being logged out
    if(isSubmitting) {
        return;
    }

    event.preventDefault();
    setIsSubmitting(true);

    const response = await sendRequest('POST', '/api/auth/logout');

    // Allow the form to be submitted again
    setIsSubmitting(false);

    return response;
}

async function logoutUser(response: Response | undefined) {

    if(!response) {
        return;
    }

    if(response.status === 204) {

        await deleteAuthToken();

        await redirectTo('login')
    }
}

export default function LogoutButton() {

    // Entire url path
    const pathName = usePathname();
    const [isSubmitting, setIsSubmitting] = useState(false)

    const isHomePage = pathName === '/';

    if(isHomePage) {
        return (<></>);
    }

    return (
        <PrimaryButton disabled={isSubmitting} onClick={(event) => {handleClick(event, isSubmitting, setIsSubmitting)
            .then((response) => logoutUser(response))}} className={"ml-auto"}>Log Out</PrimaryButton>
    );
}