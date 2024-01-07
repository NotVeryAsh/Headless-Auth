import LoginForm from "@/components/LoginForm";

function Login() {

    // TODO Add a form with inputs for email and password
    // TODO Make the form a component - this might need to be a Client Component so we can UseState to determine when the form is being submitted
    // - to prevent the user from spamming the form
    // TODO Make the individual form elements individual components
    // TODO Any of the components that require state such as input should be Client Components and use the 'use client' directive

    return (
        <>
            <h1 className="text-6xl font-bold text-slate-900">Login</h1>
            <hr className="w-5/12 h-1 bg-gray-200 rounded"></hr>
            <p id={"login-form-errors"} className={"hidden text-red-300"}></p>
            <LoginForm method={"POST"} action={"/api/auth/login"} buttonText={"Login"}>
                <input type="email" name="email" placeholder="Email" className="w-full p-2 my-2 border border-gray-300 rounded" />
                <input type="password" name="password" placeholder="Password" className="w-full p-2 my-2 border border-gray-300 rounded" />
            </LoginForm>
        </>
    );
}

export default Login;