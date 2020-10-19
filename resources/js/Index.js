import React, {useState, useEffect} from 'react';
import ReactDOM from 'react-dom';
import {BrowserRouter} from 'react-router-dom'
import {Router} from "./Router"
import {Header} from "./components/Header/Header";
import 'bootstrap/dist/css/bootstrap.min.css';
import 'font-awesome/css/font-awesome.min.css';

function Index() {
    const [isLoggedIn, setIsLoggedIn] = useState(false)

    const logInHandler = () => {
        const userLoggedIn = getLoggedInData()
        if (userLoggedIn) {
            setIsLoggedIn(true)
        }
    }

    const logOut = () => {
        localStorage.removeItem('appState')
        setIsLoggedIn(false)
    }

    useEffect(() => {
        const userLoggedIn = getLoggedInData()
        if (userLoggedIn) {
            setIsLoggedIn(true)
        }
    }, [])

    const getLoggedInData = () => {
        let userLoggedIn = false

        if (localStorage.getItem('appState') !== null) {
            userLoggedIn = JSON.parse(localStorage.getItem('appState')).isLoggedIn
        }
        return userLoggedIn
    }

    return (
        <BrowserRouter>
            <Header
                isLoggedIn={isLoggedIn}
                logOut={logOut}
            />
            <Router
                isLoggedIn={isLoggedIn}
                logInHandler={logInHandler}
            />
        </BrowserRouter>
    )
}

export default Index;

if (document.getElementById('index')) {
    ReactDOM.render(<Index />, document.getElementById('index'));
}
