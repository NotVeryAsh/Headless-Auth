'use client'

import React, {MouseEventHandler, ReactNode} from "react";

export default function Button({type="submit", disabled=false, children, onClick, className=""}: {type?: any, disabled?: boolean, children?: ReactNode, onClick?: MouseEventHandler, className?: string}) {

    className = className +
        (disabled ? " button-disabled " : "")

    return (
        <button className={className} aria-disabled={disabled} onClick={onClick} type={type}>
            {children}
        </button>
    );
}