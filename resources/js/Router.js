import React from "react"
import {Switch, Route} from "react-router-dom"
import {Flats} from "./views/Flats/Listing/Flats";
import {SingleView} from "./views/Flats/SingleView/SingleView";
import {AuthRoutes} from "./routes/AuthRoutes";
import {NotFond} from "./views/NotFound/NotFound";

export const Router = ({logInHandler, isLoggedIn}) => {

    return (
        <Switch>
            <Route exact path="/" component={Flats}/>
            <Route exact path="/flats" component={Flats} />
            <Route exact path="/flats/:flatId" component={SingleView} />
            <AuthRoutes isLoggedIn={isLoggedIn} logInHandler={logInHandler} />
            <Route component={NotFond} />
        </Switch>
    )
}
