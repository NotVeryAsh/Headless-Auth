import React from "react";

// TODO use generateStaticParams to generate the params for this page

export default function ProductsHomePage({params}: { params: { product: string } }) {
    return (
        <>
            <h1 className="text-6xl font-bold text-slate-900">Products - {params.product}</h1>
            <hr className="w-5/12 h-1 bg-gray-200 rounded"></hr>
        </>
    );
}