'use client'

import React from "react";
import {useRouter} from 'next/navigation'
import PrimaryButton from "@/components/PrimaryButton";
import { usePathname } from 'next/navigation'

export default function BackButton() {

    // Entire url path
    const router = useRouter();
    const pathName = usePathname();

    // Get all url elements which are not empty strings
    let pathArray = pathName.split('/')
        .filter((string) => string !== '');

    const isHomePage = pathArray.length === 0;

    if(isHomePage) {
        return (<></>);
    }

    let previousLocation = '';

    // previous location is second to last array index
    if (pathArray.length > 1) {
        pathArray.pop();

        previousLocation = pathArray.join('/');
    }

    return (
        <PrimaryButton onClick={() => router.push('/' + previousLocation)} className={"mr-auto"}>Back</PrimaryButton>
    );
}