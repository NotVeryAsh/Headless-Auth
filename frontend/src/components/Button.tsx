'use client'

import React, {MouseEventHandler, ReactNode} from "react";

export default function Button({disabled=false, children, onClick, className=""}: {disabled?: boolean, children?: ReactNode, onClick?: MouseEventHandler, className?: string}) {

    className = className +
        (disabled ? " button-disabled " : "")

    return (
        <button className={className} aria-disabled={disabled} onClick={onClick}>
            {children}
        </button>
    );
}