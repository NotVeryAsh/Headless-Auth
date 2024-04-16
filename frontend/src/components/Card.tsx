import React from "react";

export default function Card({title, children, className=""}: { title?: string, children?: React.ReactNode, className?: string }) {
    return (
        <div className={"bg-slate-50 flex flex-col rounded-2xl drop-shadow-md " + className}>
            {title ?
                <h2 className={"bg-teal-400 p-5 text-xl font-medium rounded-t-2xl text-white"}>{title}</h2>
                : null
            }
            <div className={"p-4 space-y-3"}>
                {children}
            </div>
        </div>
    );
}