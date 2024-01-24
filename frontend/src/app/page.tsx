import Link from "next/link";

export default function Home() {
  return (
    <main className="flex flex-col items-center justify-center space-y-10">
        <h1 className="text-6xl font-bold text-slate-900">Simple auth project</h1>
        <p className="text-2xl text-slate-600">with Next.js and Laravel</p>
        <Link href={"login"} className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Login Example
        </Link>
        <Link href={"products"} className="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
            Products Example
        </Link>
        <Link href={"calendar"} className="bg-emerald-500 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">
            Calendar Example
        </Link>
    </main>
  )
}
