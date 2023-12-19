import React from "react";
import Card from "@/components/Card";
import sendRequest from "@/lib/request";

export default async function ProductsHomePage() {
    const response = await sendRequest('GET', '/products');
    const products = response.products;
    return (
        <>
            <h1 className="text-6xl font-bold text-slate-900">Products</h1>
            <hr className="w-5/12 h-1 bg-gray-200 rounded"></hr>
            <div className={"flex flex-row space-x-5"}>
                {products.map((product: any) => (
                    <Card key={product.id} title={product.name}>
                        <p>{product.description}</p>
                        <p>Cost: {product.price}</p>
                    </Card>
                ))}
            </div>
        </>
    );
}