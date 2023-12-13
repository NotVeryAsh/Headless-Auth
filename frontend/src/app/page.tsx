import Link from "next/link";

export default function Home() {
  return (
    <main className="flex min-h-screen flex-col items-center justify-between p-24 bg-slate-100">
        <div className="flex flex-col items-center justify-center space-y-10">
            <h1 className="text-6xl font-bold text-slate-900">Simple auth project</h1>
            <p className="text-2xl text-slate-600">with TypeScript</p>
            <Link href={"login"}>
                <button className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Get Started
                </button>
            </Link>
        </div>
    </main>
  )
}
