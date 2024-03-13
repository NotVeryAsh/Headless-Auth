'use client'

import React, {MouseEventHandler, ReactNode} from "react";
import Button from "@/components/Button";

export default function DangerButton({type="button", disabled=false, children, onClick, className=""}: {type?: string, disabled?: boolean, children?: ReactNode, onClick?: MouseEventHandler, className?: string}) {

    className = className + " button-danger"

    return (
        <Button type={type} className={className} aria-disabled={disabled} onClick={onClick}>
            {children}
        </Button>
    );
}