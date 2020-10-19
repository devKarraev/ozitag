import React from "react"
import {Route, useHistory} from "react-router-dom"
import {Register} from "../views/Register/Register";
import {Login} from "../views/Login/Login";
import {NotFond} from "../views/NotFound/NotFound";

export const AuthRoutes = ({isLoggedIn, logInHandler}) => {
    const history = useHistory()

    if (history.location.pathname !== "/register" && history.location.pathname !== "/login") {
        return (
            <NotFond />
        )
    }

    if (!isLoggedIn) {
        return (
            <React.Fragment>
                <Route exact path="/register" component={() => <Register logInHandler={logInHandler} />} />
                <Route exact path="/login" component={() => <Login logInHandler={logInHandler} />} />
            </React.Fragment>
        )
    } else {
        return (
            ''
        )
    }
}

