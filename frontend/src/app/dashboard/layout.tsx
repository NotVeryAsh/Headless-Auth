import React from "react";

export default function DashboardLayout(props: {
    stats: React.ReactNode,
    activity: React.ReactNode
    children: React.ReactNode
}) {
    return (
        <>
            <h1 className="text-6xl font-bold text-slate-900">Dashboard</h1>
            <hr className="w-5/12 h-1 bg-gray-200 rounded"></hr>
            <div className={"flex flex-row space-x-5"}>
                {props.stats}
                {props.activity}
            </div>
            <div className={"flex flex-col space-x-5"}>
                {props.children}
            </div>
        </>
    );
}