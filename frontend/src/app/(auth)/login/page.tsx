import AuthenticationForm from "@/components/AuthenticationForm";
import Link from "next/link";

function Login() {

    // TODO Add a form with inputs for email and password
    // TODO Make the form a component - this might need to be a Client Component so we can UseState to determine when the form is being submitted
    // - to prevent the user from spamming the form
    // TODO Make the individual form elements individual components
    // TODO Any of the components that require state such as input should be Client Components and use the 'use client' directive

    return (
        <div className={"flex flex-col mx-auto space-y-10 items-center w-full"}>
            <h1 className="large-title">Login</h1>
            <hr className="w-3/12 h-1 bg-gray-200 rounded"></hr>
            <AuthenticationForm method={"POST"} action={"/api/auth/login"} buttonText={"Login"}>
                <input required={true} type="email" name="email" placeholder="Email" className="input" maxLength={255} />
                <input required={true} type="password" name="password" placeholder="Password" className="input" />
            </AuthenticationForm>
            <Link href={"/register"}>
                Don&apos;t have an account? Sign up here
            </Link>
        </div>
    );
}

export default Login;