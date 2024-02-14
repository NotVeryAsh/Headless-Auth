'use client'

import React, {MouseEventHandler, ReactNode} from "react";

export default function Button({disabled=false, children, onClick, classNames=""}: {disabled?: boolean, children?: ReactNode, onClick?: MouseEventHandler, classNames?: string}) {

    const ClassNames = classNames + " button" +
        (disabled ? " button-disabled" : "")

    return (
        <button className={ClassNames} aria-disabled={disabled} onClick={onClick}>
            {children}
        </button>
    );
}