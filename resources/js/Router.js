import React from "react"
import {Switch, Route} from "react-router-dom"
import {Register} from "./views/Register/Register";
import {Flats} from "./views/Flats/Listing/Flats";
import {SingleView} from "./views/Flats/SingleView/SingleView";
import {Login} from "./views/Login/Login";

export const Router = ({logInHandler, isLoggedIn}) => {

    return (
        <Switch>
            <Route exact path="/" component={Flats}/>
            <Route exact path="/flats" component={Flats} />
            <Route exact path="/flats/:flatId" component={SingleView} />
            {!isLoggedIn &&
            <React.Fragment>
                <Route exact path="/register" component={() => <Register logInHandler={logInHandler} />} />
                <Route exact path="/login" component={() => <Login logInHandler={logInHandler} />} />
            </React.Fragment>
            }
        </Switch>
    )
}
