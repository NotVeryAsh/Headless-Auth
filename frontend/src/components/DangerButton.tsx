'use client'

import React, {MouseEventHandler, ReactNode} from "react";
import Button from "@/components/Button";

export default function DangerButton({disabled=false, children, onClick, className=""}: {disabled?: boolean, children?: ReactNode, onClick?: MouseEventHandler, className?: string}) {

    className = className + " button-danger"

    return (
        <Button className={className} aria-disabled={disabled} onClick={onClick}>
            {children}
        </Button>
    );
}