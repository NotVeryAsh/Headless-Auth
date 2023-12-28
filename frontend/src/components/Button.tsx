'use client'

import React, {MouseEventHandler, ReactNode} from "react";

export default function Button({disabled=false, children, onClick, classNames=""}: {disabled?: boolean, children?: ReactNode, onClick?: MouseEventHandler, classNames?: string}) {

    const ClassNames = classNames + " bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded" +
        (disabled ? " opacity-50 cursor-not-allowed" : "")

    return (
        <button className={ClassNames} aria-disabled={disabled} onClick={onClick}>
            {children}
        </button>
    );
}