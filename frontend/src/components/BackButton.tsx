'use client'

import React from "react";
import {useRouter} from 'next/navigation'
import Button from "@/components/Button";
import { usePathname } from 'next/navigation'

export default function BackButton() {

    // Entire url path
    const router = useRouter();
    const pathName = usePathname();

    // Get all url elements which are not empty strings
    let pathArray = pathName.split('/')
        .filter((string) => string !== '');

    const isMainPage = pathArray.length === 0;

    if(isMainPage) {
        return (<></>);
    }

    let previousLocation = '';

    // previous location is second to last array index
    if (pathArray.length > 1) {
        pathArray.pop();

        previousLocation = pathArray.join('/');
    }

    return (
        <Button onClick={() => router.push('/' + previousLocation)} classNames={"mr-auto"}>Back</Button>
    );
}