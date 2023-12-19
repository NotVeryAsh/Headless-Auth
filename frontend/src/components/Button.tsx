'use client'

import React, {ReactNode} from "react";
import { useFormStatus } from "react-dom";

export default function Button({disabled=false, children}: {disabled: boolean, children?: ReactNode}) {

    const ClassNames = "bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded" +
        (disabled ? " opacity-50 cursor-not-allowed" : "")

    return (
        <button className={ClassNames} aria-disabled={disabled}>
            {children}
        </button>
    );
}