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
        </>
    );
}

export default Login;