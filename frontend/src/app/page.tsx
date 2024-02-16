import Link from "next/link";

export default function Home() {
  return (
    <div className={"flex flex-col mx-auto"}>
      <h1 className="large-title">Your Calendar</h1>
      <div className="flex flex-col space-y-8 w-5/12 text-2xl m-auto">
        <Link href={"register"} className="button-primary">
          Get Started
        </Link>
        <Link href={"login"} className="button-primary">
          Login
        </Link>
      </div>
    </div>
  )
}
