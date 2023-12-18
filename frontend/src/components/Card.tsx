import React from "react";

export default function Card({title, children}: { title?: string, children?: React.ReactNode }) {
    return (
        <div className={"bg-sky-100 flex flex-col rounded-2xl drop-shadow-sm"}>
            {title ?
                <h2 className={"bg-sky-200 p-5 text-xl font-medium rounded-t-2xl"}>{title}</h2>
                : null
            }
            <div className={"p-4 space-y-3"}>
                {children}
            </div>
        </div>
    );
}