'use client'

import React, {MouseEventHandler, ReactNode} from "react";
import Button from "@/components/Button";

export default function PrimaryButton({disabled=true, children, onClick, className="", type="submit"}: {disabled?: boolean, children?: ReactNode, onClick?: MouseEventHandler, className?: string, type?: string}) {

    className = className + " button-primary"

    return (
        <Button className={className} aria-disabled={disabled} onClick={onClick} type={type}>
            {children}
        </Button>
    );
}