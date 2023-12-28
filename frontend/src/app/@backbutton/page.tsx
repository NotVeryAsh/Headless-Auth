'use client'

import React from "react";
import {useRouter} from 'next/navigation'
import Button from "@/components/Button";
import { usePathname } from 'next/navigation'

export default function BackButton() {

    const router = useRouter();
    const pathName = usePathname();

    const hidden = pathName === '/' ? 'hidden': '';
    const classNames = hidden + ' mr-auto';

    return (
        <Button onClick={() => router.back()} classNames={classNames}>Back</Button>
    );
}